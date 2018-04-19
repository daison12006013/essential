# Essential

Easier way to build a base code dedicated to your company!

# How it Works

Sometimes you built the same directory structure, or you customized your own project; as the time goes by, you will do the same thing with future projects.
The problem here, you always do copy pasting of your **base code**, yet you can do it using this package.

# Install

```
> composer global require daison/essential
```

if your composer's binary folder is shared to the global executables, then you don't need to call the full path of the binary, or else you need to put it inside your `/usr/local/bin/`

```
> ls -n ~/.composer/vendor/bin/essential /usr/local/bin/essential
```

# Generate Config

To generate a config, you have to call

```
> essential init
```

The above command should generate a file called `essential.json`, the file contains these keys
- **template**
    - The template folder to use
- **build_path**
    - The temporary build path to use
- **replace**
    - the variables you will provide
- **scripts**
    - before
        - the scripts you would like to run before it will execute the replacer
    - after
        - the scripts you would like to run after the replacer was executed
