module.exports = function(grunt) {
	
	grunt.loadTasks('grunt');

	grunt.registerTask('build', 'Build project', function(n) {
		
		grunt.task.run('copy:fontAwesome');
		
	});
	
	grunt.registerTask('default', 'Watch files for changes', function(n) {
		
		grunt.task.run('watch');
		
	});
	
	grunt.registerTask('deploy', 'Update files', function(n) {

		grunt.task.run('jshint:beforeConcat');
		grunt.task.run('concat:jquery');
		grunt.task.run('concat:addons');
		grunt.task.run('concat:custom');
		grunt.task.run('sass:components');
		grunt.task.run('sass:custom');
		grunt.task.run('cssmin:components');
		grunt.task.run('cssmin:custom');
		grunt.task.run('uglify:jquery');
		grunt.task.run('uglify:addons');
		grunt.task.run('uglify:custom');
		grunt.task.run('imagemin:category');
		grunt.task.run('imagemin:fullscreen');
		grunt.task.run('imagemin:project');
		
	});

};