// 'use strict';

module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		sass: {
			dist: {
				options : {
					style: 'compressed',
					sourcemap: 'none'
				},
				files: [{
					expand: true,
					cwd: 'scss/',
					src: ['*.scss'],
					dest: 'css/',
					ext: '.min.css'
				}]
			},
			dev: {
				options : {
					style: 'expanded',
					sourcemap: 'file'
				},
				files: [{
					expand: true,
					cwd: 'scss/',
					src: ['*.scss'],
					dest: 'css/',
					ext: '.css'
				}]
			}
		},

		watch: {
			sass: {
				files: '**/*.scss',
				tasks: ['sass']
			}
		}

	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	// grunt.loadNpmTasks('grunt-contrib-uglify');
	// grunt.loadNpmTasks('grunt-contrib-concat');

	grunt.registerTask('default', ['sass:dist', 'sass:dev']);
}
