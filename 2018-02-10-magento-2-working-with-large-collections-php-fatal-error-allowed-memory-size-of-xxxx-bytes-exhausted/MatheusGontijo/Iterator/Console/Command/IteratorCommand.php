<?php

namespace MatheusGontijo\Iterator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IteratorCommand extends Command
{
    /** @var \Magento\Framework\Model\ResourceModel\Iterator $iterator */
    protected $iterator;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
    protected $collection;

    /** @var \Magento\Catalog\Api\ProductRepositoryInterface $productFactory */
    protected $productRepository;

    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Framework\Model\ResourceModel\Iterator $iterator,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $state->setAreaCode('frontend');

        $this->iterator          = $iterator;
        $this->collection        = $collection;
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('matheusgontijo:iterator')
            ->setDescription('Iterator command');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        you can filter by attributes
//        $this
//            ->collection;
//            ->addAttributeToFilter('name', array('like' => '%samsung%'));

        $this->collection->setPageSize(10);

        $this
            ->iterator
            ->walk(
                $this->collection->getSelect(),
                array(array($this, 'callback'))
            );
    }

    public function callback($args)
    {
        // load of the product
        $product = $this->productRepository->getById($args['row']['entity_id']);

        // do whatever you want to with the $product
        print_r($product->debug());
    }
}
