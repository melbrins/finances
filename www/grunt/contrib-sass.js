module.exports = function(grunt) {
	
	grunt.config('sass', {

		custom: {

			options: {
				style		: 'expanded',
				compass		: true,
				lineNumber	: true
			},

			files: {
				'dest/css/main.css'  : 'scss/stylesheet.scss'
			}
		}
		
	});
	
	grunt.loadNpmTasks('grunt-contrib-sass');
};