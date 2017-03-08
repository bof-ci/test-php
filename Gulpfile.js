var gulp = require('gulp');
var phpspec = require('gulp-phpspec');
var run = require('gulp-run');
var notify = require('gulp-notify');

gulp.task('bof', function() {
    gulp.src('spec/**/*.php')
        .pipe(run('clear'))
        .pipe(phpspec('', {notify: true }))
        .on('error', notify.onError({
            title: 'Oh no.',
            message: 'Your tests failed, Nicolas',
            icon: __dirname + '/fail.png',
            sound: 'Funk'
        }))
        .pipe(notify({
            title: 'Success.',
            message: 'All tests have returned green',
            icon: __dirname + '/success.png',
            sound: 'Purr'
        }));
});

gulp.task('watch', function () {
    gulp.watch(['spec/BOF/Command/**/*.php', 'src/Command/**/*.php'], ['bof'])
});

gulp.task('default', ['bof', 'watch']);
    
