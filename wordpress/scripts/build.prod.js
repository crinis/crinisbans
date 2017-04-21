process.env.NODE_ENV = 'production'

var webpack = require('webpack')
var ora = require('ora')
var webpackConfig = require('../webpack.prod.config')

var spinner = ora('building for production...')
spinner.start()

webpack(webpackConfig, function (err, stats) {
  spinner.stop()
})