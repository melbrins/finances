module.exports = function(grunt){

    // This automatically loads grunt tasks from node_modules
    require('load-grunt-tasks')(grunt);
    // This installs timers so you can monitor how log each of your build steps take
    require("time-grunt")(grunt);

    // Project Configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),

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
        browserify:{
            build:{
                src: 'script/common.js',
                dest: 'script/main.js'
            }
        },
        // grunt-contrib-concat
        concat:{
            build:{
                src: 'script/main.js',
                dest: 'js/main.js'
            }
        },
        uglify:{
            build:{
                src: 'js/main.js',
                dest: 'js/main.min.js'
            }
        },
        // grunt-babel
        babel:{
            dist: {
                files:{
                    'js/main.js' :"js/main.js"
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
                files: 'scss/stylesheet.scss',
                tasks: ['sass']

            }
        }
    });

    grunt.registerTask("build", ["browserify", "concat"]);


    grunt.registerTask("default", ["build"]);
};