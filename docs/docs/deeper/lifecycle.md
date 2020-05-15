# Lifecycle

## Introduction

Fjord is an admin interface for editing and managing data, a content management system. It is possible to write your own packages or extend existing packages. In order to understand how and where it makes most sense to do this, it is important to understand the lifecycle of the Fjord application.

## Lifecycle Overview
### First Things

Fjord comes to life in its main ServiceProvider `Fjord\FjordServiceProvider` when the Fjord application instance gets created in the `register` method of the provider.

### Kernel

Next, the kernel is created and initialized. The kernel defines an array of `bootstrappers` that will be run before the request is executed. This contains the discovering of all packages that are set in its `composer.json` and the registration of its ServiceProviders. 

As well the kernel builds the Fjord Vue application befor creating the `fjord::app` view. This is done by checking the data that is passed to the view, preparing the props and then executing all extensions for the given vue component.


