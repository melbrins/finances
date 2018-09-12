module.exports = function(grunt) {
	
	// Project configuration.
	grunt.config( 'unused', {
	    images: {
	    	options: {
	    		reference: 'images/',
	    		directory: ['**/*.php', '**/*.scss'],
	    		reportOutput:'report.txt'
	    	}
	    }
	});

	grunt.loadNpmTasks('grunt-unused');
};