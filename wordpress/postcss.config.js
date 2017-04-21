module.exports = {
  plugins: [
    require('postcss-smart-import')({ /* ...options */ }),
    require('postcss-assets')({ 
      baseUrl: '/wp-content/plugins/crinisbans/dist/',
      loadPaths: ['img/', 'scripts/', 'styles/', 'fonts/'],
      cachebuster: true
    }),
    require('precss')({ /* ...options */ }),
    require('autoprefixer')({ /* ...options */ }),
    require('cssnext')({ /* ...options */ }),
    require('postcss-apply')(),
    require('postcss-scopify')('.cb')
  ]
}