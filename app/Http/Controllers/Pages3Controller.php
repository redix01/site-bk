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
        return view('pages-3.home');
    }

    /**
     * Display the legal page from pages-3 directory
     */
    public function legal()
    {
        return view('pages-3.legal');
    }

    /**
     * Display home sub-pages
     */
    public function homeAssetManagement()
    {
        return view('pages-3.home.asset-management');
    }

    public function homeInternationalBanking()
    {
        return view('pages-3.home.international-banking');
    }

    public function homePrivateBanking()
    {
        return view('pages-3.home.private-banking');
    }

    public function homeContact()
    {
        return view('pages-3.home.contact');
    }

    public function homeOurCompany()
    {
        return view('pages-3.home.our-company');
    }

    public function homeMedia()
    {
        return view('pages-3.home.media');
    }

    public function homeInvestorRelations()
    {
        return view('pages-3.home.investor-relations');
    }

    public function homeInstitutionalClients()
    {
        return view('pages-3.home.institutional-clients');
    }

    /**
     * Display legal sub-pages
     */
    public function legalWhistleblowing()
    {
        return view('pages-3.legal.whistleblowing');
    }

    public function legalAeoi()
    {
        return view('pages-3.legal.aeoi');
    }

    public function legalDataProtection()
    {
        return view('pages-3.legal.data-protection');
    }

    public function legalTermsConditions()
    {
        return view('pages-3.legal.terms-conditions');
    }

    public function legalTradingAndInvestmentBusiness()
    {
        return view('pages-3.legal.trading-and-investment-business');
    }

    public function legalConflictOfInterest()
    {
        return view('pages-3.legal.conflict-of-interest');
    }

    /**
     * Display LPS pages
     */
    public function lpsPrivateBanking()
    {
        return view('pages-3.lps.private-banking');
    }

    public function lpsCorporateBerichterstattung()
    {
        return view('pages-3.lps.corporate.berichterstattung');
    }
}
