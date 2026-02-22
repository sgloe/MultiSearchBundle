# MultiSearchBundle

This bundle provides basic service and form type for Multi Search in Doctrine.

## Description

Search in all of entity columns by given search term.
In response returns `Doctrine\ORM\QueryBuilder` containing the multiple search criteria.
The searched columns can be specified.

## Installation

### Using composer

    composer require sgloe/multi-search-bundle

Add it to your `config/bundles.php`:

    Sgloe\MultiSearchBundle\SgloeMultiSearchBundle::class => ['all' => true],

## Usage

### Service

You can inject the service and apply the multi search to any Doctrine query builder.

    use Sgloe\MultiSearchBundle\Service\MultiSearchBuilderService;

    public function __construct(
        private MultiSearchBuilderService $multiSearchBuilder,
        private EntityManagerInterface $entityManager,
    ) {}

    public function index(Request $request): Response
    {
        $search = $request->query->get('search');

        $qb = $this->entityManager->getRepository(Post::class)->createQueryBuilder('e');
        $qb = $this->multiSearchBuilder->searchEntity($qb, Post::class, $search);
        //$qb = $this->multiSearchBuilder->searchEntity($qb, Post::class, $search, ['name', 'content'], 'wildcard');

        ..
    }

### Form

Create your form type and include the multiSearchType in the buildForm function:

    use Sgloe\MultiSearchBundle\Form\Type\MultiSearchType;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', MultiSearchType::class, [
                'class' => Post::class, //required
                'search_fields' => [ //optional, if it's empty it will search in all entity columns
                    'name',
                    'content',
                ],
                'search_comparison_type' => 'wildcard', //optional, what type of comparison to apply ('wildcard','starts_with', 'ends_with', 'equals')
            ])
        ;
    }

In the controller, inject the service and call the multi search:

    use Sgloe\MultiSearchBundle\Service\MultiSearchBuilderService;

    public function __construct(
        private MultiSearchBuilderService $multiSearchBuilder,
        private EntityManagerInterface $entityManager,
    ) {}

    public function index(Request $request): Response
    {
        $queryBuilder = $this->entityManager->getRepository(Post::class)->createQueryBuilder('e');
        $filterForm = $this->createForm(PostFilterType::class);

        // Bind values from the request
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            // Build the query from the given form object
            $queryBuilder = $this->multiSearchBuilder->searchForm($queryBuilder, $filterForm->get('search'));
        }

        ..
    }

Render your form in the view

    {{ form_rest(filterForm) }}

## Available Options

The provided type has 2 options:

* `search_fields` - array of the entity columns that will be added in the search. If it's not set then will search in
  all columns
* `search_comparison_type` - how to compare the search term.

    * `wildcard` - it's equivalent to the %search% like search.

    * `equals` - like operator without wildcards. Wildcards still can be used with `equals` if the search term
      contains *.

    * `starts_with` - it's equivalent to the %search like search.

    * `ends_with` - it's equivalent to the search% like search.

These parameters can be applyed to the service as well as 4th and 5th parameter to `searchEntity()` method

## Author

Sgloe

Thanks to Petko Petkov, who originally wrote this bundle. Since it is not maintained anymore, I started this fork.

## License

MultiSearchBundle is licensed under the MIT License.



