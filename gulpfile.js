var gulp = require('gulp');
var jshint = require('gulp-jshint');
var rev = require('gulp-rev');
var del = require('del');
var concat = require('gulp-concat');

var paths = require('./gulp.config.php.json');

gulp.task('criarRevisao', criarRevisao);
gulp.task('concatenar', concatenar);
gulp.task('limpar', limpar);

function criarRevisao() {
    gulp.src('build/all.min.js')
            .pipe(rev())
            .pipe(gulp.dest(paths.build));
}

function concatenar() {
    gulp.start('limpar');
    gulp.src([].concat(paths.js))
            .pipe(concat('all.min.js'))
            .pipe(gulp.dest(paths.build));
}

function limpar() {
    del(paths.build);
}