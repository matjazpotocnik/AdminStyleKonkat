@echo off
setlocal
pushd "%~dp0"
call lessc --rewrite-urls=all less/build.less admin.css
if errorlevel 1 goto failed
call lessc --rewrite-urls=all --clean-css less/build.less admin.min.css
if errorlevel 1 goto failed
popd
exit /b 0
:failed
popd
exit /b 1
