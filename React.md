**SETUP REACT WITH GRUNT**

The first thing that come to my mind when I think about using react with grunt is to find the npm module and to connect it to my grunt routine.

From an initial search I found an interesting article about how to do exactly that: http://billpatrianakos.me/blog/2017/02/03/using-react-with-webpack-and-es6-in-a-grunt-task/

At first they ask to install Webpack and Babel, let’s have a look what those are.


**WEBPACK**

At its core, webpack is a static module bundler for modern JavaScript applications. When webpack processes your application, it internally builds a dependency graph which maps every module your project needs and generates one or more bundles.

https://webpack.js.org/concepts/




**Entry point**

It’s like the main scss file importing all the others, by default it’s set up to be ./src/index.js

    module.exports = {
      entry: './path/to/my/entry/file.js'
    };
    


**Output Point**

Where to the file that has been generated, by default it’s ./dist/main.js for the main output and ./dist for all other generated files.

    
    const path = require('path');
    
    module.exports = {
      entry: './path/to/my/entry/file.js',
      output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'my-first-webpack.bundle.js'
      }
    };


**Loaders**

Allow you to import other type of files than Json and Js. It’s useful to set up module dependencies with images and other files.
    
    const path = require('path');
    
    module.exports = {
      output: {
        filename: 'my-first-webpack.bundle.js'
      },
      module: {
        rules: [
          { test: /\.txt$/, use: 'raw-loader' }
        ]
      }
    };


**Plugins**

While loaders are used to transform certain types of modules, plugins can be leveraged to perform a wider range of tasks like bundle optimization, asset management and injection of environment variables.

In order to use a plugin, you need to require() it and add it to the plugins array. Most plugins are customizable through options. Since you can use a plugin multiple times in a config for different purposes, you need to create an instance of it by calling it with the new operator.
    
    const HtmlWebpackPlugin = require('html-webpack-plugin'); //installed via npm
    const webpack = require('webpack'); //to access built-in plugins
    
    module.exports = {
      module: {
        rules: [
          { test: /\.txt$/, use: 'raw-loader' }
        ]
      },
      plugins: [
        new HtmlWebpackPlugin({template: './src/index.html'})
      ]
    };

**Mode**

By setting the mode parameter to either development, production or none, you can enable webpack's built-in optimizations that correspond to each environment. The default value is production.

    
    module.exports = {
      mode: 'production'
    };



**Babel**

Babel is a toolchain that is mainly used to convert ECMAScript 2015+ code into a backwards compatible version of JavaScript in current and older browsers or environments. Here are the main things Babel can do for you:

* Transform syntax
* Polyfill features that are missing in your target environment (through @babel/polyfill)
* Source code transformations (codemods)
* And more! (check out these videos for inspiration)



