module.exports = function(grunt) {
	
	// Project configuration.
	grunt.config( 'jshint', {
	    beforeConcat: {
	    	options: {
	    		unused: true,
	    		force: true
	    	},

	    	files:{ 
	    		src: [
		    		'script/*.js'
		    	]
	    	}
	    },

	    afterConcat: [
	    	'dest/js/main.min.js'
	    ]
	});

	grunt.loadNpmTasks('grunt-contrib-jshint');
};