/**
 * Gruntfile.js
 */
module.exports = function(grunt) {
	grunt.initConfig({
	pkg: grunt.file.readJSON('package.json'),
		



		sass: {
			default: {
				options: {
					includePaths: ['public/guide/components/foundation/scss']
				},
				files: {
					'public/guide/css/guide.css': 'public/guide/src/css/guide.scss'
				}
			}
		},
		autoprefixer: {
			options: {
				browsers: ['> 0%'],
				diff:true
			},
			default: {
				files: {

					'public/guide/css/guide.css': 'public/guide/css/guide.css'
				}
			}
		},
		cssmin: {
			default: {
				files: {
					'public/guide/css/guide.css': 'public/guide/css/guide.css'
				}
			}
		},


		import: {
			default: {
				files:  {
					'public/guide/js/guide.js': 'public/guide/src/js/guide.js'					
				}
			}

		},

		uglify: {
			options: {
				sourceMap: true
			},
			default: {
				files: {
					'public/guide/js/guide.js': 'public/guide/js/guide.js',
					'public/guide/js/modernizr.js' : 'public/guide/components/modernizr/modernizr.js'
				}
			}
		},




		watch: {
			grunt: { 
				files: ['Gruntfile.js'], 
				tasks: ['build']
			},

			sass_frontend: {
				files: ['public/guide/src/css/**/*.scss'],
				tasks: ['css']
			},
			js_frontend: {
				files: ['public/guide/src/js/**/*.js'],
				tasks: ['js']
			}
		},
		

	});

	// load standard tasks
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-import');	
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// 
	grunt.registerTask('build', ['css', 'js']);
	grunt.registerTask('css', ['sass', 'autoprefixer', 'cssmin']);
	grunt.registerTask('js', ['import', 'uglify']);

	grunt.registerTask('default', ['watch', 'build']);

};
