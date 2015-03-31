<?php

namespace Gregumo\StarsRentalBundle\Controller;

use Gregumo\StarsRentalBundle\Entity\Customer;
use Gregumo\StarsRentalBundle\Entity\Vehicle;
use Gregumo\StarsRentalBundle\Utils\BookingUpgrader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Gregumo\StarsRentalBundle\Entity\Booking;
use Gregumo\StarsRentalBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Response;


/**
 * Booking controller.
 *
 * @Route("/booking")
 */
class BookingController extends Controller
{

    /**
     * Lists all Booking entities.
     *
     * @Route("/", name="booking")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GregumoStarsRentalBundle:Booking')->findAll();
        $customerNb = $em->getRepository('GregumoStarsRentalBundle:Customer')->getNb();
        $vehicleNb = $em->getRepository('GregumoStarsRentalBundle:Vehicle')->getAvailableNb();

        return array(
            'entities' => $entities,
            'hideAddButton' => $vehicleNb == 0 || $customerNb == 0
        );
    }

    /**
     * Creates a new Booking entity.
     *
     * @Route("/", name="booking_create")
     * @Method("POST")
     * @Template("GregumoStarsRentalBundle:Booking:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Booking();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('booking_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Booking entity.
     *
     * @param Booking $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Booking $entity)
    {
        $form = $this->createForm(new BookingType(), $entity, array(
            'action' => $this->generateUrl('booking_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Booking entity.
     *
     * @Route("/new", name="booking_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Booking();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Booking entity.
     *
     * @Route("/{id}", name="booking_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GregumoStarsRentalBundle:Booking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Booking entity.
     *
     * @Route("/{id}/edit", name="booking_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GregumoStarsRentalBundle:Booking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Booking entity.
     *
     * @param Booking $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Booking $entity)
    {
        $form = $this->createForm(new BookingType(), $entity, array(
            'action' => $this->generateUrl('booking_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Booking entity.
     *
     * @Route("/{id}", name="booking_update")
     * @Method("PUT")
     * @Template("GregumoStarsRentalBundle:Booking:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GregumoStarsRentalBundle:Booking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('booking_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Booking entity.
     *
     * @Route("/{id}", name="booking_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GregumoStarsRentalBundle:Booking')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Booking entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('booking'));
    }

    /**
     * Creates a form to delete a Booking entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('booking_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Check if a booking is upgradable and response with JSON
     *
     * @Route("/isUpgradable/{id}/{customer_id}", name="booking_is_upgradable")
     * @ParamConverter("customer", class="GregumoStarsRentalBundle:Customer", options={"id" = "customer_id"})
     * @Method("GET")
     * @Template()
     */
    public function isUpgradableAction(Vehicle $vehicle, Customer $customer)
    {
        /* @var BookingUpgrader $bu */
        $bu = $this->get('gregumo_starsrental.utils.booking_upgrader');

        $response = new Response(json_encode($bu->getUpgradableInfos($vehicle, $customer)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
