# PANTHERWAGEN!

Drupal 8 test project to test PANTHER: https://github.com/symfony/panther

## Environments

Env | Branch | Drush alias | URL
--- | ------ | ----------- | ---
development | * | - | http://pantherwagen.fi.docker.amazee.io/
production | master | @master | TBD

## Requirements

You need to have these applications installed to operate on all environments:

- [Docker](https://github.com/druidfi/guidelines/blob/master/docs/docker.md)
- [Pygmy](https://github.com/druidfi/guidelines/blob/master/docs/pygmy.md)
- For the new person: Your SSH public key needs to be added to servers

## Create and start the environment aka HOW TO TEST

For the first time (new project):

```
$ make new
```
You will get login/password reset link from this command.

Then, generate some dummy articles:

```
$ make shell
$ cd ~/public_html/
$ drush dummycontent:generate

```

## Login to Drupal container

This will log you inside the Drupal Docker container and in the `public` folder:

```
$ make shell
```

## Read more

- [Contrib modules included](https://github.com/druidfi/spell/blob/master/docs/contrib.md)
- [Project structure](https://github.com/druidfi/spell/blob/master/docs/structure.md)
- [Quality assurance](https://github.com/druidfi/spell/blob/master/docs/qa.md)
- [FAQ](https://github.com/druidfi/spell/blob/master/docs/faq.md)
