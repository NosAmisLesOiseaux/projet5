<?php

// src/Services/Pagination/NAOPagination.php

namespace App\Services\Pagination;

class NAOPagination
{
	private $nbElementsPerPage;
	private $nbHomeCapturesPerPage;
	private $nbBirdsPerPage;

	public function __construct()
	{
		$this->nbElementsPerPage = '1';
		$this->nbHomeCapturesPerPage = '4';
		$this->nbBirdsPerPage = '15';
	}

    /**
     * @return string
     */
	public function getNbElementsPerPage()
	{
		return $this->nbElementsPerPage;
	}

    /**
     * @return string
     */
	public function getNbHomeCapturesPerPage()
	{
		return $this->nbHomeCapturesPerPage;
	}

    /**
     * @return string
     */
	public function getNbBirdsPerPage()
	{
		return $this->nbBirdsPerPage;
	}

    /**
     * @param $totalElements
     * @param $nbElementsPerPage
     * @return float
     */
	public function CountNbPages($totalElements, $nbElementsPerPage)
	{
		return $nbPages = ceil($totalElements/$nbElementsPerPage);
	}

    /**
     * @param $page
     * @param $totalElements
     * @param $nbElementsPerPage
     * @return float|int
     */
	public function getFirstEntrance($page, $totalElements, $nbElementsPerPage)
	{
		return $firstEntrance = ($page - 1) * $nbElementsPerPage;
	}

    /**
     * @param $page
     * @return int
     */
	public function getNextPage($page) 
	{
		return $nextp = $page + 1;
	}

    /**
     * @param $page
     * @return int
     */
	public function getPreviousPage($page)
	{
		return $previousp = $page - 1;
	}
}
