# Essential

Easier way to build a base code dedicated to your company!

# How it Works

Sometimes you built the same directory structure, or you customized your own project; as the time goes by, you will do the same thing with future projects.
The problem here, you always do copy pasting of your **base code**, yet you can do it using this package.

# Install

```
> composer require daison/essential --dev
```

# Generate Config

To generate a config, you have to call

```
> ./vendor/bin/essential init
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

### What it does:

- a VARIABLE based replacer to your own templates
- On your template folder, it actually iterates all the files; let us say you have 'AUTHOR_NAME' inside your config and all files that has {AUTHOR_NAME} will be replaced.
- You can put bash scripts in it as a json, as long as you follow the rules of json format!

### Where should you use it?

- Most probably creating your own project installer?
- Write your own base code dedicated to all your projects? (We're using this actually on my current company and it really helps a lot of time copy-pasting the same base code we have)

### Missing Implementations:

- Unit testing is still ongoing and will add this in the TravisCI.org
- Sample way thru a Video Recorded (Will do later)
