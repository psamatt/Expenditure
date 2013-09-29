<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MonthExpenditureController extends BaseController
{
    /**
     * Mark an expenditure as paid
     *
     * @param integer $expenditureID The expenditure to mark as paid
     * @param Request $request
     * @return 1
     */
    public function paidAction($expenditureID, Request $request)
    {
        $expenditure = $this->em->getRepository('Expenditure:MonthExpenditure')->find($expenditureID);
        
        $this->isOwnedByAdmin($expenditure->getHeader());

        $amount = $request->get('amount');

        $expenditure->setAmountPaid($amount == 'all'? $expenditure->getPrice(): floatval($amount));

        $this->em->persist($expenditure);
        $this->em->flush();

        return 1;
    }

    /**
     * Save a new expenditure item
     *
     * @param integer $headerID The header
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($headerID, Request $request)
    {
        $expenditureID = $request->get('expenditureID');
        $title = $request->get('title');
        $price = $request->get('price');

        if (strlen($title) > 0 && strlen($price) > 0) {
        
            $monthHeader = $this->em->getRepository('Expenditure:MonthHeader')->find($headerID);
            $this->isOwnedByAdmin($monthHeader);

            if ($expenditureID > 0) {
                $expenditure = $this->em->getRepository('Expenditure:MonthExpenditure')->find($expenditureID);
            } else {
                $expenditure = new \Expenditure\Entity\MonthExpenditure;
            }

            $expenditure->setTitle($title);
            $expenditure->setPrice($price);
            $expenditure->setHeader($monthHeader);

            $this->em->persist($expenditure);
            $this->em->flush();
        }

        return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
    }

    /**
     * Delete an expenditure item
     *
     * @param integer $expenditureID The expenditure ID
     * @return 1
     */
    public function deleteAction($expenditureID)
    {
        $expenditure = $this->em->getRepository('Expenditure:MonthExpenditure')->find($expenditureID);
        
        $this->isOwnedByAdmin($expenditure->getHeader());

        $this->em->remove($expenditure);
        $this->em->flush();

        return 1;
    }
}