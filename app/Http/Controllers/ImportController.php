<?php

namespace App\Http\Controllers;

use App\Imports\ImportData;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerApprove;
use App\Models\CustomerContract;
use App\Models\CustomerInsurance;
use App\Models\CustomerSurvey;
use App\Models\Education;
use App\Models\Installment;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Loan;
use App\Models\Maritial;
use App\Models\Provinsi;
use App\Models\Religion;
use App\Models\Savings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('import.index');
    }

    public function submit(Request $req)
    {
        $this->validate($req, [
            'import-data' => 'required|file',
        ]);

        $data = Excel::toArray(new ImportData(), $req->file('import-data'));
        if (count($data) != 5) {
            return redirect()->back()->withErrors('Harus ada 4 tab sheet pada format excel yang diinput');
        }

        DB::beginTransaction();

        try {
            
            // import data pengajuan
            if (count($data[0]) < 6) { // data harus dimulai dari urutan baris ke 6
                return redirect()->back()->withErrors('Data dari pengajuan harus dimulai dari cell A6');
            }
            for ($i = 5; $i < count($data[0]); $i++) {
    
                $rowData = $data[0][$i];
    
                $customer = new Customer();
    
                $index = 0;
    
                $customer->member_number = $rowData[$index++];
                $customer->reg_number = $rowData[$index++];
    
                $company = Company::where('company_id', $rowData[$index++])->first();
                $customer->branch = $company->id;
    
                $customer->name = $rowData[$index++];
                $customer->mobile_phone = $rowData[$index++];
                $customer->birth_place = $rowData[$index++];
                $customer->date_of_birth = $rowData[$index++];
                $customer->family_card_number = $rowData[$index++];
                $customer->card_number = $rowData[$index++];
                $customer->mother_maiden_name = $rowData[$index++];
                $customer->gender = $rowData[$index++];
    
                $provinsi = Provinsi::where(DB::raw('UPPER(nama)'), "=", $rowData[$index++])->first();
                $customer->provinsi = $provinsi->id;
    
                $kabupaten = Kabupaten::where(DB::raw('UPPER(nama)'), "=", $rowData[$index++])->first();
                $customer->kabupaten = $kabupaten->id;
    
                $kecamatan = Kecamatan::where(DB::raw('UPPER(nama)'), "=", $rowData[$index++])->first();
                $customer->kecamatan = $kecamatan->id;
    
                $kelurahan = Kelurahan::where(DB::raw('UPPER(nama)'), "=", $rowData[$index++])->first();
                $customer->kelurahan = $kelurahan->id;
    
                $education = Education::where('code', $rowData[$index++])->first();
                $customer->education = $education->id;
    
                $religion = Religion::where(DB::raw('UPPER(name)'), '=', $rowData[$index++])->first();
                $customer->religion = $religion->id;
    
                $customer->address = $rowData[$index++];
                $customer->zip_code = $rowData[$index++];
    
                // skip index 18 karena pemisah antar kategori
                $index++;
    
                $customer->company_name = $rowData[$index++];
                $customer->department = $rowData[$index++];
                $customer->part = $rowData[$index++];
                $customer->kpk_number = $rowData[$index++];
                $customer->personalia_name = $rowData[$index++];
                $customer->net_salary = $rowData[$index++];
                $customer->gross_salary = $rowData[$index++];
                $customer->payday_date = $rowData[$index++];
                $customer->bank_name = $rowData[$index++];
                $customer->bank_number = $rowData[$index++];
    
                // skip index 29 karena pemisah antar kategori
                $index++;
    
                $maritial = Maritial::where(DB::raw('UPPER(name)'), '=', $rowData[$index++])->first();
                $customer->maritial = $maritial->id;
    
                $customer->husband_wife = $rowData[$index++];
                $customer->alias_husband_wife = $rowData[$index++];
                $customer->husband_wife_profession = $rowData[$index++];
                $customer->husband_wife_income = $rowData[$index++];
                $customer->husband_wife_phone = $rowData[$index++];
                $customer->husband_wife_address = $rowData[$index++];
                $customer->husband_wife_home_status = $rowData[$index++];
    
                // skip index 38 karena pemisah antar kategori
                $index++;
    
                $customer->family_father = $rowData[$index++];
                $customer->family_mother = $rowData[$index++];
                $customer->family_address = $rowData[$index++];
                $customer->in_law_father = $rowData[$index++];
                $customer->in_law_mother = $rowData[$index++];
                $customer->in_law_phone = $rowData[$index++];
                $customer->in_law_address = $rowData[$index++];
                $customer->connection_name = $rowData[$index++];
                $customer->connection_alias_name = $rowData[$index++];
                $customer->connection_phone = $rowData[$index++];
                $customer->connection_address = $rowData[$index++];
                $customer->family_connection = $rowData[$index++];
    
                // skip index 51 karena pemisah antar kategori
                $index++;
    
                $customer->loan_amount = $rowData[$index++];
                $customer->loan_to = $rowData[$index++];
                $customer->time_period = $rowData[$index++];
                $customer->necessity_for = $rowData[$index++];
                $customer->survey_plan = $rowData[$index++];
    
                $customer->save();
            }
    
            // import data survey
            if (count($data[1]) < 3) { // data harus dimulai dari urutan baris ke 3
                return redirect()->back()->withErrors('Data dari survey harus dimulai dari cell A3');
            }
    
            for ($i = 2; $i < count($data[1]); $i++) {
                $rowData = $data[1][$i];
    
                $survey = new CustomerSurvey();
    
                $index = 0;
    
                $customer = Customer::where('reg_number', $rowData[$index])->first();
    
                $survey->customer_id = $customer->id;
                $survey->reg_number = $rowData[$index++];
                $survey->environment_condition = $rowData[$index++];
                $survey->viability = $rowData[$index++];
                $survey->child_fee = $rowData[$index++];
                $survey->electricity_cost = $rowData[$index++];
                $survey->water_cost = $rowData[$index++];
                $survey->other_installment = $rowData[$index++];
                $survey->cost_of_living = $rowData[$index++];
                $survey->husband_wife_income = $customer->husband_wife_income;
    
                $survey->save();
            }
    
            if (count($data[2]) < 3) {
                return redirect()->back()->withErrors('Data dari pengesahan harus dimulai dari cell A3');
            }
    
            for ($i = 2; $i < count($data[2]); $i++) {
                $rowData = $data[2][$i];
    
                $approval = new CustomerApprove();
    
                $index = 0;
    
                $customer = Customer::where('reg_number', $rowData[$index])->first();
    
                $approval->customer_id = $customer->id;
                $approval->reg_number = $rowData[$index++];
                $approval->approve_amount = $rowData[$index++];
                $approval->installment = $rowData[$index++];
                $approval->time_period = $rowData[$index++];
                $approval->interest_rate = $rowData[$index++];
                $approval->m_savings = $rowData[$index++];
                $approval->approve = strtoupper($rowData[$index]) == "APPROVE";
                
                $approval->save();
            }
    
            if (count($data[3]) < 3) {
                return redirect()->back()->withErrors('Data dari pengesahan harus dimulai dari cell A3');
            }
    
            for ($i = 2; $i < count($data[3]); $i++) {
                $rowData = $data[3][$i];
    
                $index = 0;
    
                $customer = Customer::where('reg_number', $rowData[$index++])->first();
    
                $contract = new CustomerContract();
                $contract->customer_id = $customer->id;
                $contract->reg_number = $customer->reg_number;
                $contract->contract_date = $customer->created_at->format('Y-m-d');
    
                $company = Company::find($customer->branch);
    
                $contract->contract_number = $contract->contractNumber($customer->created_at, $company->company_id);
    
                $customerApprove = CustomerApprove::where('customer_id', $customer->id)->first();

                $contract->m_savings = $customerApprove->m_savings;
    
                $nomorPinjaman = $rowData[$index++];
    
                $contract->provision = $rowData[$index++];
    
                $perusahaanAsuransi = $rowData[$index++];
    
                $contract->insurance = $rowData[$index++];
                $contract->stamp = $rowData[$index++];
                $contract->deskripsi = $rowData[$index++];
                $contract->status = "BELUM LUNAS";
                $contract->branch = $company->id;
    
                $contract->save();
    
                $customer->member_number = $customer->memberNumber($company->company_id, $customer->id);
                $customer->status = 'member';
                $customer->member = 1;
    
                $customer->save();
    
                $loan = new Loan();
                $loan->loan_number = $nomorPinjaman;
                $loan->customer_id = $customer->id;
                $loan->contract_number = $contract->contract_number;
                $loan->contract_date = $contract->contract_date;
                $loan->start_month = now()->addMonth();
                $loan->member_number = $customer->member_number;
    
                $sukuBunga = $customerApprove->interest_rate / 12;
                $pokok = $customerApprove->approve_amount / $customerApprove->time_period;
                $bunga = $customerApprove->approve_amount * $sukuBunga / 100;
                $jumlahAngsuran = $pokok + $bunga + $customerApprove->m_savings;
                
                $loan->loan_amount = $jumlahAngsuran * $customerApprove->time_period;
                $loan->time_period = $customerApprove->time_period;
                $loan->pay_date = $customer->payday_date;
                $loan->interest_rate = $customerApprove->interest_rate;
                $loan->pay_principal = $pokok;
                $loan->pay_interest = $bunga;
                $loan->pay_month = $jumlahAngsuran;
                $loan->company_id = $company->id;
                $loan->loan_remaining = $jumlahAngsuran * $customerApprove->time_period;
                $loan->status = 'BELUM LUNAS';
    
                $loan->save();
    
                $customerInsurance = new CustomerInsurance();
                $customerInsurance->customer_id = $customer->id;
                $customerInsurance->no_kontrak = $customerInsurance->nomorKontrak($customer->id);
                $customerInsurance->duration = $customerApprove->time_period;
                $customerInsurance->name_user = $customer->name;
                $customerInsurance->branch = $company->id;
                $customerInsurance->company = $perusahaanAsuransi;
    
                $customerInsurance->save();
    
                $saving = new Savings();
                $saving->proof_number = $saving->nomorTabungan();
                $saving->member_number = $customer->member_number;
                $saving->tr_date = now()->format('Y-m-d');
                $saving->branch = $company->id;
                $saving->tipe = 'pokok';
                $saving->status = 'setor';
                $saving->amount = $customerApprove->m_savings;
                $saving->description = "Pembayaran Tab. Pokok Pinjaman";
                $saving->end_balance = 0;
    
                $saving->save();
    
            }

            $customer->journal_pencairan($customer->id);

            if (count($data[4]) < 3) {
                return redirect()->back()->withErrors('Data dari angsuran harus dimulai dari cell A3');
            }

            for ($i = 2; $i < count($data[4]); $i++) {
                $rowData = $data[4][$i];
                $installment = new Installment();

                $index = 0;

                $installment->loan_number = $rowData[$index++];
                $installment->inst_to =   $rowData[$index++];
                $installment->pay_date =   $rowData[$index++];
                $installment->pay_method =   $rowData[$index++];
                $installment->due_date =   $rowData[$index++];
                $installment->pay_status =   $rowData[$index++];
                $installment->pay_principal =   $rowData[$index++];
                $installment->pay_rates =   $rowData[$index++];
                $installment->saving =   $rowData[$index++];
                $installment->late_charge =   $rowData[$index++];
                $installment->t_installment =   $rowData[$index++];
                // $installment->late_charge =   $installment->pay_principal + $installment->pay_rates  + $installment->saving + $installment->saving + $installment->late_charge;
                $installment->amount = $rowData[$index++];
                $installment->status = $rowData[$index++];

                $installment->save();

            }

            DB::commit();
            return redirect()->back()->with('success', 'Sukses import data');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal import data');
        }
    }
}
