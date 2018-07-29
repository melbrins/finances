module.exports = function(grunt) {
	
	// Project configuration.
	grunt.config( 'uglify', {
		jquery: {
	      files: {
	        'dest/js/jquery.min.js': ['js/jquery.js']
	      }
	    },

	    addons: {
	      files: {
	        'dest/js/addons.min.js': ['js/addons.js']
	      }
	    },

	    custom: {
	    	options: {
	    		sourceMap: true
	    	},

	      	files: {
	        	'dest/js/main.min.js': ['js/main.js']
	      	}
	    }
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
};