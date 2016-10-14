/**
 * Grunt file
 */

module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		sass: {
			css: {
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
			},
			css_min: {
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
			// css_plugins: {
			// 	options : {
			// 		style: 'nested',
			// 		sourcemap: 'none'
			// 	},
			// 	files: [{
			// 		expand: true,
			// 		cwd: 'plugins/scss/',
			// 		src: ['*.scss'],
			// 		dest: 'plugins/css/',
			// 		ext: '.min.css'
			// 	}]
			// },
			css_onepage: {
				options : {
					style: 'expanded',
					sourcemap: 'file'
				},
				files: [{
					expand: true,
					cwd: 'onepage/scss/',
					src: ['*.scss'],
					dest: 'onepage/css/',
					ext: '.css'
				}]
			},
			css_min_onepage: {
				options : {
					style: 'compressed',
					sourcemap: 'none'
				},
				files: [{
					expand: true,
					cwd: 'onepage/scss/',
					src: ['*.scss'],
					dest: 'onepage/css/',
					ext: '.min.css'
				}]
			}
		},

		uglify: {
			js: {
				options: {
					mangle: false
				},
				files: [{
					expand: true,
					cwd: 'js/',
					src: ['*.js', '!*.min.js'],
					dest: 'js/',
					ext: '.min.js'
				}]
			},
			js_plugins: {
				options: {
					mangle: false
				},
				files: [{
					expand: true,
					cwd: 'plugins/js/',
					src: ['*.js', '!*.min.js'],
					dest: 'plugins/js/',
					ext: '.min.js'
				}]
			},
			js_onepage: {
				options: {
					mangle: false
				},
				files: [{
					expand: true,
					cwd: 'onepage/js/',
					src: ['*.js', '!*.min.js'],
					dest: 'onepage/js/',
					ext: '.min.js'
				}]
			}
		},

		imagemin: {
			images: {
				options: {
					optimizationLevel: 5,
					progressive: true
				},
				files: [{
					expand: true,
					cwd: 'images/',
					src: ['**/*.{png,jpg,gif}', '!~*', '!_*', '!~/*'],
					dest: 'img/'
				}]
			}
		},

		watch: {
			sass: {
				files: ['scss/*.scss'],
				tasks: ['sass:css', 'sass:css_min']
			},
			// sass_plugins: {
			// 	files: ['plugins/scss/*.scss'],
			// 	tasks: ['sass:css_plugins', 'sass:css_min_plugins']
			// },
			sass_onepage: {
				files: ['onepage/scss/*.scss'],
				tasks: ['sass:css_onepage', 'sass:css_min_onepage']
			},
			uglify: {
				files: ['js/*.js', '!js/*.min.js'],
				tasks: ['uglify:js']
			},
			uglify_plugins: {
				files: ['plugins/js/*.js', '!plugins/js/*.min.js'],
				tasks: ['uglify:js_plugins']
			},
			uglify_onepage: {
				files: ['onepage/js/*.js', '!onepage/js/*.min.js'],
				tasks: ['uglify:js_onepage']
			},
			imagemin: {
				files: ['images/*.png', 'images/*.jpg'],
				tasks: ['imagemin']
			}
		}

	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['sass', 'uglify', 'imagemin']);
}
