<?php

// Forms
use Silex\Provider\FormServiceProvider; // http://php.net/manual/fr/language.namespaces.importing.php
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;


$app->register(new FormServiceProvider());

$contact = $app['controllers_factory'];

$contact->get('/', function () use ($app) {
  $data = array(
      'name' => 'Your name',
      'email' => 'Your email',
  );

  $form = $app['form.factory']->createBuilder(FormType::class, $data)
      ->add('name')
      ->add('email')
      ->add('billing_plan', ChoiceType::class, array(
          'choices' => array(1 => 'free', 2 => 'small_business', 3 => 'corporate'),
          'expanded' => true,
      ))
      ->getForm();

  // display the form
  return $app['twig']->render('contact.twig', array(
    'form' => $form->createView(),
    'title' => 'Contact'));
});

return $contact;
