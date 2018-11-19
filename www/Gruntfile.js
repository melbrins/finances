module.exports = function(grunt){

    // This automatically loads grunt tasks from node_modules
    require('load-grunt-tasks')(grunt);
    // This installs timers so you can monitor how log each of your build steps take
    require("time-grunt")(grunt);

    // Project Configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),
        //grunt-contrib-copy
        copy: {
            libraries: {
                files: [
                    {
                        expand: true,
                        cwd: 'bower_components/components-font-awesome/webfonts/',
                        src: ['**/*'],
                        dest: 'dest/webfonts/'
                    },
                    {
                        expand: true,
                        cwd: 'bower_components/components-font-awesome/scss/',
                        src: ['**/*'],
                        dest: 'scss/fontawesome/'
                    }
                ]
            }
        },
        // grunt-contrib-jshint
        jshint: {
            all: {
                // Monitors all files
                src: ["Gruntfile.js", "script/*.js"]
            },
            options: {
                // Strongly recommended to use .jshintrc, that way editor can use it too.
                // jshintrc: "./.jshintrc"
            }
        },
        // grunt-browserify
        //
        // Download libraries
        //
        browserify:{
            build:{
                src: [
                    'script/common.js'
                ],
                dest: 'script/main.js'
            }
        },
        // grunt-contrib-concat
        //
        // Join all js source in one file
        //
        concat:{
            build:{
                src: [
                    'script/main.js'
                ],
                dest: 'js/main.js'
            }
        },
        // grunt-contrib-uglify
        uglify:{
            build:{
                src: 'js/main.js',
                dest: 'js/main.min.js'
            }
        },
        // grunt-babel
        babel:{
            options:{
                sourceType: module
            },
            dist: {
                files:{
                    'js/main.js' :"js/main.js"
                }
            }
        },
        // grunt-contrib-compass
        compass: {
            dist: {
                options: {
                    fontDir: 'dest/fonts',
                    sassDir: 'scss',
                    cssDir: 'dest/css',
                    sourcemap: true
                }
            }
        },
        // grunt-contrib-sass
        sass:{
            options: {
                style		: 'expanded',
                compass		: true,
                lineNumber	: true
            },

            build:{
                src: 'scss/stylesheet.scss',
                dest: 'dest/css/main.css'
            }
        },
        // grunt-contrib-watch
        watch:{
            js:{
                files: 'script/common.js',
                tasks: ["browserify", "concat"]
            },

            sass:{
                files: ['scss/*.scss', 'scss/*/*.scss'],
                tasks: ['sass']

            }
        }
    });

    grunt.registerTask("build", ["browserify", "concat", "copy", "sass"]);


    grunt.registerTask("default", ["build"]);
};