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

	public function getNbElementsPerPage()
	{
		return $this->nbElementsPerPage;
	}

	public function getNbHomeCapturesPerPage()
	{
		return $this->nbHomeCapturesPerPage;
	}

	public function getNbBirdsPerPage()
	{
		return $this->nbBirdsPerPage;
	}

	public function CountNbPages($totalElements, $nbElementsPerPage)
	{
		return $nbPages = ceil($totalElements/$nbElementsPerPage);
	}

	public function getFirstEntrance($page, $totalElements, $nbElementsPerPage)
	{
		return $firstEntrance = ($page - 1) * $nbElementsPerPage;
	}

	public function getNextPage($page) 
	{
		return $nextp = $page + 1;
	}

	public function getPreviousPage($page)
	{
		return $previousp = $page - 1;
	}
}