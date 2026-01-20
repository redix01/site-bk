<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pages3Controller extends Controller
{
    /**
     * Display the home page from pages-3 directory
     */
    public function home()
    {
        return view('pages3.home');
    }

    /**
     * Display the legal page from pages-3 directory
     */
    public function legal()
    {
        return view('pages3.legal');
    }

    /**
     * Display home sub-pages
     */
    public function homeAssetManagement()
    {
        return view('pages3.home.asset-management');
    }

    public function homeInternationalBanking()
    {
        return view('pages3.home.international-banking');
    }

    public function homePrivateBanking()
    {
        return view('pages3.home.private-banking');
    }

    public function homeContact()
    {
        return view('pages3.home.contact');
    }

    public function homeOurCompany()
    {
        return view('pages3.home.our-company');
    }

    public function homeMedia()
    {
        return view('pages3.home.media');
    }

    public function homeInvestorRelations()
    {
        return view('pages3.home.investor-relations');
    }

    public function homeInstitutionalClients()
    {
        return view('pages3.home.institutional-clients');
    }

    public function homeOurCompanyBoardOfDirectors()
    {
        return view('pages3.home.our-company.board-of-directors');
    }

    public function homeOurCompanyCorporateGovernance()
    {
        return view('pages3.home.our-company.corporate-governance');
    }

    public function homeOurCompanyCorporateStrategy()
    {
        return view('pages3.home.our-company.corporate-strategy');
    }

    public function homeOurCompanyExecutiveBoard()
    {
        return view('pages3.home.our-company.executive-board');
    }

    public function homeOurCompanyHistory()
    {
        return view('pages3.home.our-company.history');
    }

    public function homeOurCompanyMissionStatement()
    {
        return view('pages3.home.our-company.mission-statement');
    }

    public function homeOurCompanyPublicServiceMandate()
    {
        return view('pages3.home.our-company.public-service-mandate');
    }

    /**
     * Display legal sub-pages
     */
    public function legalWhistleblowing()
    {
        return view('pages3.legal.whistleblowing');
    }

    public function legalAeoi()
    {
        return view('pages3.legal.aeoi');
    }

    public function legalDataProtection()
    {
        return view('pages3.legal.data-protection');
    }

    public function legalTermsConditions()
    {
        return view('pages3.legal.terms-conditions');
    }

    public function legalTradingAndInvestmentBusiness()
    {
        return view('pages3.legal.trading-and-investment-business');
    }

    public function legalConflictOfInterest()
    {
        return view('pages3.legal.conflict-of-interest');
    }

    public function legalCompanyStructure()
    {
        return view('pages3.home.our-company.company-structure');
    }

    public function legalGeneralInformation()
    {
        return view('pages3.legal.general-information');
    }

    public function legalGips()
    {
        return view('pages3.legal.global-investment-performance-standards-gips');
    }

    public function legalKycAmlPatriotAct()
    {
        return view('pages3.legal.kyc-aml-patriot-act');
    }

    public function legalLegalNoticesWebsites()
    {
        return view('pages3.legal.legal-notices-websites');
    }

    public function legalTrustServices()
    {
        return view('pages3.legal.trust-services');
    }

    /**
     * Display LPS pages
     */
    public function lpsPrivateBanking()
    {
        return view('pages3.lps.private-banking');
    }

    public function lpsCorporateBerichterstattung()
    {
        return view('pages3.lps.corporate.berichterstattung');
    }
}
