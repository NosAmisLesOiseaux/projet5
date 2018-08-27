<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 27/08/18
 * Time: 11:27
 */

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaxrefController
 * @package App\Controller\Frontend
 * @Route(path="taxref/")
 */
class TaxrefController extends AbstractController
{
    /**
     * @Route(path="rangs-taxonomiques/")
     */
    public function rangsTaxonomiques()
    {
        $request = json_decode(file_get_contents("https://taxref.mnhn.fr/api/rangsTaxonomiques"));
        return new JsonResponse($request);
    }
    /**
     * @Route(path="versions/")
     */
    public function versions()
    {
        $request = json_decode(file_get_contents("https://taxref.mnhn.fr/api/taxrefVersions"));
        return new JsonResponse($request);
    }
    /**
     * @Route(path="status/")
     */
    public function status()
    {
        $request = json_decode(file_get_contents("https://taxref.mnhn.fr/api/statuts/types"));
        return new JsonResponse($request);
    }
    /**
     * @Route(path="groups/ver")
     */
    public function groupsV()
    {
        $request = json_decode(file_get_contents("https://taxref.mnhn.fr/api/groupesVernaculaires"));
        return new JsonResponse($request);
    }
    /**
     * @Route(path="groups/op")
     */
    public function groupsO()
    {
        $request = json_decode(file_get_contents("https://taxref.mnhn.fr/api/groupesOperationnels"));
        return new JsonResponse($request);
    }
}
