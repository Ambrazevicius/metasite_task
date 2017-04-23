<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Random;
use AppBundle\Entity\Subscribers;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Validator\Constraints\Blank;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class DefaultController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {


        $logged = false;
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $logged = true;
        }

        $model = new Subscribers();

        $form = $this->createFormBuilder($model, [
            'validation_groups' => array('subscribe')
        ])->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control col-md-6',
                    'placeholder' => 'Your name',
                ],
                'required'=> false,
                'label' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your email address',
                ],
                'required'=> false,
                'label' => false,
            ])
            ->add('categories', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control col-md-12',
                    'placeholder' => 'Your Choice',
                ],
                'label' => false,
                'choices'  => $model->getSelection(),
                'expanded'  => true,
                'multiple'  => true,
                'error_bubbling' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-block'
                ]
            ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $validator = $this->get('validator');
            $errors = $validator->validate($model);

            if (count($errors) > 0) {

                $model->setErrors($errors);
                return $this->render('default/index.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $model->getErrors(),
                    'logged' => $logged
                ));

            }else{

                $idi = new Random();
                $dateTime = new \DateTime();

                $model->setId($idi->alpha());
                $model->setCreatedAt($dateTime->format('Y-m-d H:i:s'));

                $data = $form->getData();

                $inp = file_get_contents('list.json');
                $tempArray = json_decode($inp);
                array_push($tempArray, $data);
                $jsonData = json_encode($tempArray);
                file_put_contents('list.json', $jsonData);

                return $this->render('default/submit.html.twig', array(
                    'form' => $form->createView(),
                    'cats' => $form->get('categories')->getData(),
                    'logged' => $logged
                ));

            }

        }else{
            return $this->render('default/index.html.twig', array(
                'form' => $form->createView(),
                'errors' => $model->getErrors(),
                'logged' => $logged
            ));
        }
    }


}
