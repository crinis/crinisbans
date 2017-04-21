#!groovy​

/*
This pipeline has no use at the moment and is used for testing
*/
node {
    try {
        stage('Clear Workspace and Checkout Source') {
            deleteDir()
            checkout scm
        }

        stage('Get composer production dependencies') {
            docker.image('composer/composer:1.0-alpine').inside {
                sh 'composer --working-dir=./wordpress install --no-dev'
            }
        }

        stage('Get node dependencies and build for production') {
            docker.image('node:6-alpine').inside {
                sh 'npm -C wordpress install'
                sh 'npm -C wordpress run prod'
            }
        }

        stage('Move WordPress build artifacts into a specific directory') {
            sh 'mkdir -p build/wordpress/'
            sh 'mv wordpress/crinisbans/ build/wordpress/'
        }

        stage('Build Sourcemod plugins') {
            sh 'docker pull crinis/builder-sourcemod'
            def sourcemod = docker.image('crinis/builder-sourcemod')
            sourcemod.pull()
            sourcemod.inside('--user root') {
                sh 'cp -R sourcemod/* /home/builder/sourcemod/addons/sourcemod/scripting/'
                sh 'bash /home/builder/sourcemod/addons/sourcemod/scripting/compile.sh crinisbans.sp'
                sh 'bash /home/builder/sourcemod/addons/sourcemod/scripting/compile.sh crinisbans_admins.sp'
                sh 'bash /home/builder/sourcemod/addons/sourcemod/scripting/compile.sh crinisbans_bans.sp'
                sh 'mkdir -p build/sourcemod/addons/sourcemod/plugins/ build/sourcemod/addons/sourcemod/scripting/ build/sourcemod/cfg/sourcemod/'
                sh 'mv /home/builder/sourcemod/addons/sourcemod/scripting/compiled/* build/sourcemod/addons/sourcemod/plugins/'
                sh 'cp -R sourcemod/*.sp build/sourcemod/addons/sourcemod/scripting/'
                sh 'cp sourcemod/cfg/crinisbans.cfg build/sourcemod/cfg/sourcemod/'
                sh 'chown --reference sourcemod/ -R build/sourcemod/'
            }
        }

        stage('Compress build artifacts') {
            sh "mv build/ crinisbans-${BUILD_ID}/"
            sh "tar -czf crinisbans-${BUILD_ID}.tar.gz crinisbans-${BUILD_ID}/"
        }

        stage('Archive build artifacts') {
            archiveArtifacts "crinisbans-${BUILD_ID}.tar.gz"
        }

        stage('Cleanup workspace') {
            deleteDir()
        }

    } catch (Exception e) {
        throw e;
    } finally {

    }
}