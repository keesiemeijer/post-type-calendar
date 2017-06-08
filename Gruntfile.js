module.exports = function( grunt ) {

	// Load multiple grunt tasks using globbing patterns
	require( 'load-grunt-tasks' )( grunt );

	'use strict';
	var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		gitinfo: {},

		// Clean up build directory
		clean: {
			main: [ 'build/<%= pkg.name %>' ]
		},

		// Copy the theme into the build directory
		copy: {
			main: {
				src: [
					'**',
					'!node_modules/**',
					'!bin/**',
					'!tests/**',
					'!build/**',
					'!vendor/**',
					'!.git/**',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!.gitattributes',
					'!.editorconfig',
					'!.tx/**',
					'!**/Gruntfile.js',
					'!**/package.json',
					'!**/phpunit.xml',
					'!**/phpunit.xml.dist',
					'!**/README.md',
					'!**/readme.md',
					'!**/CHANGELOG.md',
					'!**/CONTRIBUTING.md',
					'!**/travis.yml',
					'!**/*~'
				],
				dest: 'build/<%= pkg.name %>/'
			}
		},

		// read version from package.json
		version: {
			readmetxt: {
				options: {
					prefix: 'Stable tag: *'
				},
				src: [ 'readme.txt' ]
			},
			tested_up_to: {
				options: {
					pkg: {
						"version": "<%= pkg.tested_up_to %>"
					},
					prefix: 'Tested up to: *'
				},
				src: [ 'readme.txt', 'README.md' ]
			},
			requires_at_least: {
				options: {
					pkg: {
						"version": "<%= pkg.requires_at_least %>"
					},
					prefix: 'Requires at least: *'
				},
				src: [ 'readme.txt', 'README.md' ]
			},
			plugin: {
				options: {
					prefix: 'Version: *'
				},
				src: [ 'README.md', 'post-type-calendar.php' ]
			},
		},

		// composer update
		composer: {
			build: {
				options: {
					cwd: 'build/post-type-calendar',
				}
			}
		}

	} );

	grunt.loadNpmTasks( 'grunt-composer' );

	grunt.registerTask( 'composer_install', function( key, value ) {

		// install packages, compose with Mozart and update with --no-dev
		grunt.task.run( 'composer:build:install' );
	} );

	grunt.registerTask( 'build', [ 'clean', 'version', 'copy', 'composer_install' ] );

	grunt.util.linefeed = '\n';

};