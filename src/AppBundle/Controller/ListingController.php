<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Blank;
use AppBundle\Entity\Subscribers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListingController extends Controller
{
    /**
     * @Route("/listing/", name="listing")
     */
    public function indexAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('listing/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);

    }

    /**
     * @Route("/listing/delete", name="listing_delete")
     */
    public function deleteAction(Request $request){

        $content = file_get_contents(Subscribers::FILE);
        $json = json_decode($content, true);

        $id = $request->getContent();

        $status = 'silence';
        foreach ($json as $key => $value) {
            if (in_array($id, $value)) {
                unset($json[$key]);
                $status = 'Deleted';
            }
        }

        $json = json_encode(array_values($json));
        file_put_contents(Subscribers::FILE, $json);

        return new JsonResponse([
            'status' => $status,
            'id' => $id
        ]);

    }

    /**
     * @Route("/listing/view/{id}", name="listing_view")
     */
    public function viewAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $content = file_get_contents(Subscribers::FILE);
        $json = json_decode($content, true);

        $output = '';
        foreach ($json as $key => $value) {
            if (in_array($id, $value)) {
                $output = $json[$key];
            }
        }

        $output['url_all'] = new RequestContext('/');

        return $this->render('listing/view.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'output' => $output
        ]);

    }


    /**
     * @Route("/listing/edit/{id}", name="listing_edit")
     */
    public function editAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $content = file_get_contents(Subscribers::FILE);
        $json = json_decode($content, true);

        $output = '';
        foreach ($json as $key => $value) {
            if (in_array($id, $value)) {
                $output = $json[$key];
            }
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
            'data' => $output['name']
        ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your email address',
                ],
                'required'=> false,
                'label' => false,
                'data' => $output['email']
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
                'data' => $output['categories'],
                'error_bubbling' => true,
            ])
            ->add('save', SubmitType::class, [
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
                return $this->render('listing/edit.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $model->getErrors(),
                    'success' => null,
                    'id' => $id
                ));

            }else{

                foreach ($json as $key => $value) {
                    if (in_array($id, $value)) {
                        unset($json[$key]);
                    }
                }
                $json = json_encode(array_values($json));
                file_put_contents(Subscribers::FILE, $json);

                $dateTime = new \DateTime();
                $model->setCreatedAt($dateTime->format('Y-m-d H:i:s'));
                $model->setId($id);
                $data = $form->getData();

                $inp = file_get_contents(Subscribers::FILE);
                $tempArray = json_decode($inp);
                array_push($tempArray, $data);
                $jsonData = json_encode(array_values($tempArray));
                file_put_contents(Subscribers::FILE, $jsonData);

                return $this->render('listing/edit.html.twig', array(
                    'form' => $form->createView(),
                    'success' => 'Subscriber updated succesfully!',
                    'errors' => null,
                    'id' => $id
                ));

            }

        }else{
            return $this->render('listing/edit.html.twig', array(
                'form' => $form->createView(),
                'errors' => $model->getErrors(),
                'success' => null,
                'id' => $id
            ));
        }

    }

}
