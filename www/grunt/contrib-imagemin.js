module.exports = function(grunt) {
	
	// Project configuration.
	grunt.config( 'imagemin', {
	    fullscreen: {
	    	options: {
	    		optimizationLevel: 7
	    	},

            files: [{
                expand: true,
                cwd: 'images/fullscreen',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'dest/images/fullscreen/'
            }]
        },

        project: {
	    	options: {
	    		optimizationLevel: 7
	    	},

            files: [{
                expand: true,
                cwd: 'images/project',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'dest/images/project/'
            }]
        },

        category: {
        	options: {
        		optimizationLevel: 7
        	},

        	files: [{
        		expand: true,
        		cwd: 'images/work',
        		src: ['**/*.{png,jpg,gif}'],
                dest: 'dest/images/work/'
        	}]
        }
	});

	grunt.loadNpmTasks('grunt-contrib-imagemin');
};