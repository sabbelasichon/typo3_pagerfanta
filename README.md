[![Downloads](https://img.shields.io/packagist/dt/ssch/typo3-pagerfanta.svg?style=flat-square)](https://packagist.org/packages/ssch/typo3-pagerfanta)

TYPO3 integration with Pagerfanta Library!
==========================================

Extension to use [Pagerfanta](https://github.com/BabDev/Pagerfanta) with [TYPO3](https://github.com/TYPO3/typo3).

## Installation & Setup

To install this bundle, run the following Composer command:

```bash
composer require ssch/typo3-pagerfanta
```

## Default configuration
```php
plugin.tx_typo3pagerfanta {
    settings {
        # The default Pagerfanta view to use in your application
        default_view = fluid
        # The default fluid template to use when using the Twig Pagerfanta view (available: Foundation6, MaterializeCss, Tailwind, TwitterBootstrap, TwitterBootstrap3, TwitterBootstrap4, TwitterBootstrap5
        default_fluid_template = EXT:typo3_pagerfanta/Resources/Private/Templates/TwitterBootstrap5.html
    }
}
```

## How to use by examples

Imagine you have a classic extbase plugin with an extbase repository query result you want to paginate, then
you can use the following example as a starter.

```php
use Psr\Http\Message\ResponseInterface;

final class TestController extends ActionController
{
    public function myCustomAction(int $currentPage = 1): ResponseInterface
    {
        $jobs = $this->jobRepository->findAll();

        $queryResultAdapter = new QueryResultAdapter($jobs);
        $pagination = Pagerfanta::createForCurrentPageWithMaxPerPage($queryResultAdapter, $currentPage, 1);

        $this->view->assign('pagination', $pagination);

        return $this->htmlRepsonse();
    }
}
```

You then call the PaginationViewHelper in your Fluid template, passing in the Pagination instance.
The routes are generated automatically for the current route using the variable "currentPage" to propagate the page number. By default, the extension uses the FluidView with the TwitterBootstrap5 template to render the pagination.

```html
{namespace pagerfanta = Ssch\Typo3Pagerfanta\ViewHelpers}
<pagerfanta:pagination pagerfanta="{pagination}" />
<f:for each="{pagination}" as="job">
    <f:render partial="List" arguments="{job: job}"/>
</f:for>
<pagerfanta:pagination pagerfanta="{pagination}" />
```

If you are using a parameter other than currentPage for pagination, you can set the parameter name by using the pageParameter option when rendering the pager.

```html
<pagerfanta:pagination pagerfanta="{pagination}" options="{pageParameter: 'page'}" />
```

If you want to use a different template for the pagination you can set the template by using the template option when rendering the pager.

```html
<pagerfanta:pagination pagerfanta="{pagination}" options="{template: 'EXT:typo3_pagerfanta/Resources/Private/Templates/Foundation6.html'}" />
```

## Why this extension shines

In my opinion this extension or better to say the Pagerfanta concept separates the route generation (RouteGenerator) from the view.
So the view is totally agnostic about how to generate the page links itself and so it is fully reusable.
To generate the routes inside the controller offers more flexibility instead of generating the page links inside the view itself.

## Further Documentation

Please see the [BabDev website](https://www.babdev.com/open-source/packages/pagerfanta/docs/3.x/intro) for detailed information on how to use this extension.

## Acknowledgment
This package is heavily inspired by [babdev/pagerfanta-bundle](https://github.com/BabDev/PagerfantaBundle) by Michael Babker. Thank you.


