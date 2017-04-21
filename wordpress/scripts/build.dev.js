process.env.NODE_ENV = 'development'

var webpack = require('webpack')
var ora = require('ora')
var webpackConfig = require('../webpack.dev.config')

var spinner = ora('building for development...')
spinner.start()

webpack(webpackConfig, function (err, stats) {
	spinner.stop()
})