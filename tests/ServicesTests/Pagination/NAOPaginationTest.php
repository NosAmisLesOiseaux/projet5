<?php

// test/Services/Pagination/NAOPaginationTest.php

namespace App\Services\Pagination;

use App\Services\Pagination\NAOPagination;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NAOPaginationTest extends WebTestCase
{
	private $naoPagination;
	private $totalElements;
	private $nbElementsPerPage;
	private $page;
	 /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->totalElements = '50';
        $this->nbElementsPerPage = '10';
        $this->page = '2';
        $this->naoPagination = $kernel->getContainer()->get('app.nao_pagination');
    }

    public function testCountPages()
    {
    	$numberPages = $this->naoPagination->CountNbPages($this->totalElements, $this->nbElementsPerPage);

    	$this->assertEquals('5', $numberPages);
    }

    public function testGetFirstEntrance()
    {
    	$firstEntrancePage2 = $this->naoPagination->getFirstEntrance($this->page, $this->totalElements, $this->nbElementsPerPage);

    	$this->assertEquals('10', $firstEntrancePage2);
    }

    public function testGetNextPage()
    {
    	$pageNextPage2 = $this->naoPagination->getNextPage($this->page);

    	$this->assertEquals('3', $pageNextPage2);
    }

    public function testGetPreviousPage()
    {
    	$pagePreviousPage2 = $this->naoPagination->getPreviousPage($this->page);

    	$this->assertEquals('1', $pagePreviousPage2);
    }
}