module.exports = function(grunt) {
	
	// Project configuration.
	grunt.config( 'copy', {
		fontAwesome: {
	      	files: [{
	      		expand: true,
	      		cwd: 'node_modules/font-awesome/fonts/',
	      		src: '*',
	      		dest: 'fonts/font-awesome/'
	      	}]
	    }
	});

	grunt.loadNpmTasks('grunt-contrib-copy');
};