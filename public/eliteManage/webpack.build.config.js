var webpack = require('webpack')
var join = require('path').join

module.exports = 
{
    context: join(__dirname, 'src'),
    entry: 
    {
        admin: './admin.js',
        teacher: './teacher.js',
        student: './student.js',
        vindicator: './vindicator.js'
    },
    output: 
    {
        path: join(__dirname, 'dist'),
        filename: '[name].js'
    },
    resolve: {extensions: ['', '.js', '.json', '.vue'] },
    module: 
    {
        loaders: 
        [
            { test: /\.js$/, exclude: /node_modules/, loader: 'babel' },
            { test: /\.json$/, loader: 'json' },
            { test: /\.vue$/, loader: 'vue' },
            { test: /\.(png|jpg)$/, loader: 'url' }
        ]
    },
    babel: 
    {
        presets: ['es2015'],
        plugins: ['transform-runtime']
    },
    vue: 
    {
        autoprefixer: {browsers: ['last 2 versions'] }
    },
    plugins: 
    [
        new webpack.DefinePlugin({'process.env': {NODE_ENV: '"production"'} }),
        new webpack.optimize.UglifyJsPlugin({compress:{warnings:false },output:{comments:false}})
    ]
}
