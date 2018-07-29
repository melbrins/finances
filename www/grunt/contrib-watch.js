module.exports = function(grunt) {
	
	grunt.config('watch', {
		
		// Combine Custom JS together
		// Minify newly created JS Files from previous task
		js: {
			files: ['./script/*.js'],
			tasks: ['jshint:beforeConcat', 'concat:custom', 'uglify:custom']
		},		
		
		// Generate Css file from custom scss files
		// Minify newly created CSS file
		scss: {
			files: ['./scss/*.scss', './scss/*/*.scss'],
			tasks: ['sass:custom', 'cssmin:custom']
		}
		
	});
	
	grunt.event.on('watch', function(action, filepath, target) {
		grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
};