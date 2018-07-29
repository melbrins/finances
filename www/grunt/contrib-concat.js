module.exports = function(grunt) {
	
	grunt.config('concat', {
		
		jquery: {
			
			options: {
				
			},
			
			src: [
                'js/src/jquery.min.js',
                'node_modules/jquery-ui-dist/jquery-ui.js'
				],

			dest: 'js/jquery.js'
			
		},

		addons:{

			options:{

			},

			src: [
				'node_modules/moment/min/moment.min.js',
				'node_modules/chart.js/dist/chart.min.js',
				'js/jquery.comiseo.daterangepicker.min.js'
			],

			dest: 'js/addons.js'
		},

		custom:{

			options: {
				sourceMap: true
			},

			src: [
				'script/*.js'
			],

			dest: 'js/main.js'
		}

		
	});
	
	grunt.loadNpmTasks('grunt-contrib-concat');
};