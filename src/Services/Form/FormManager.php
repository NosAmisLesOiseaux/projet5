<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 21/08/18
 * Time: 18:38
 */

namespace App\Services\Form;


use App\Entity\Capture;
use App\Form\Capture\NaturalistCaptureType;
use App\Form\Capture\ParticularCaptureType;
use Symfony\Component\Form\FormFactoryInterface;

class FormManager
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $role
     * @param Capture $capture
     * @return bool|\Symfony\Component\Form\FormInterface
     */
    public function getCaptureForm(string $role, Capture $capture)
    {
        if ($role === 'particulier')
        {
            $form = $this->formFactory->create(ParticularCaptureType::class, $capture);
            return $form;
        }
        elseif ($role === 'naturaliste' || $role === 'administrateur')
        {
            $form = $this->formFactory->create(NaturalistCaptureType::class, $capture);
            return $form;
        } else {
            return false;
        }
    }
}
