module.exports = function(grunt) {
	
	grunt.config('cssmin', {
		components: {
			files: {
				'dest/css/components.min.css' : 'css/components.css'
			}
		},

		custom: {
			options: {
				rebase: true,
				sourceMap: true
			},

			files: {
				'dest/css/main.min.css' : 'css/main.css'
			}
		}
		
	});
	
	grunt.loadNpmTasks('grunt-contrib-cssmin');
};