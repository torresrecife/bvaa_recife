<?php

namespace App\Http\Controllers;

use App\Grids\ProcessosGrid;
use App\Grids\ProcessosGridInterface;
use App\Processos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Result;

class ProcessosController extends Controller
{
    protected $request;
    /**
     * Endpoit constructor
     * @param $request;
     *
    */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProcessosGridInterface $processosGrid
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banks = [
            'RCJ VAREJO - BANCO J. SAFRA S/A',
            'BUSCA - BANCO J. SAFRA S/A',
            'BUSCA - BANCO SAFRA S/A',
            'BUSCA - SAFRA LEASING S/A ARRENDAMENTO MERCANTIL',
            'RCJ VAREJO - BANCO SAFRA S/A',
            'RCJ VAREJO - SAFRA LEASING S/A ARRENDAMENTO MERCANTIL'
        ];

        $query = DB::connection('sqlsrv')
            ->table('Processo')
            ->where('StatusProcesso','Ativo')
            ->whereIn('Carteira',$banks)
            ->select('CodigoProcesso','Carteira','StatusProcesso','FaseProcesso','Area');

        return (new ProcessosGrid()) // you can then use it as $this->user within the class. It's set implicitly using the __set() call
            ->create(['query' => $query, 'request' => $request])
            ->renderOn('processos_grid');
    }

    public function distribuidos($area="")
    {
        if (isset($area)){
            $banks = $this->purse($area);
            $query = DB::connection('sqlsrv')->table('Processo');
            $query->where('StatusProcesso','Ativo');
            $query->whereNull('TipoDesdobramento');
            $query->where('FaseProcesso', 'like','%distribuida%');
            $query->whereIn('Carteira',$banks);

            //get filters
            isset($this->request->CodigoProcesso)?$query->where('CodigoProcesso',$this->request->CodigoProcesso):'';
            isset($this->request->Area)?$query->where('Area',$this->request->Area):'';
            isset($this->request->TipoProcesso)?$query->where('TipoProcesso',$this->request->TipoProcesso):'';
            isset($this->request->Comarca)?$query->where('Comarca',$this->request->Comarca):'';
            isset($this->request->FaseProcesso)?$query->where('FaseProcesso',$this->request->FaseProcesso):'';
            isset($this->request->Carteira)?$query->where('Carteira',$this->request->Carteira):'';
            isset($this->request->StatusProcesso)?$query->where('StatusProcesso',$this->request->StatusProcesso):'';

            $query->orderBy('DataCriacao','desc');

            //Create Coluns to filters
            $codigoProcesso = [];
            $coluns['CodigoProcesso'] = [];
            $coluns['Area'] = [];
            $coluns['TipoProcesso'] = [];
            $coluns['Comarca'] = [];
            $coluns['FaseProcesso'] = [];
            $coluns['Carteira'] = [];
            $coluns['StatusProcesso'] = [];

            foreach ($query->get() as $item) {
                if(!in_array($item->CodigoProcesso,$coluns['CodigoProcesso'])){
                    $coluns['CodigoProcesso'][$item->CodigoProcesso] = $item->CodigoProcesso;
                    $codigoProcesso[] = $item->CodigoProcesso;
                }
                if(!in_array($item->Area,$coluns['Area'])){ $coluns['Area'][$item->Area] = $item->Area;}
                if(!in_array($item->TipoProcesso,$coluns['TipoProcesso'])){ $coluns['TipoProcesso'][$item->TipoProcesso] = $item->TipoProcesso;}
                if(!in_array($item->Comarca,$coluns['Comarca'])){ $coluns['Comarca'][$item->Comarca] = $item->Comarca;}
                if(!in_array($item->FaseProcesso,$coluns['FaseProcesso'])){ $coluns['FaseProcesso'][$item->FaseProcesso] = $item->FaseProcesso;}
                if(!in_array($item->Carteira,$coluns['Carteira'])){ $coluns['Carteira'][$item->Carteira] = $item->Carteira;}
                if(!in_array($item->StatusProcesso,$coluns['StatusProcesso'])){ $coluns['StatusProcesso'][$item->StatusProcesso] = $item->StatusProcesso;}
            }

            //hitory data base
            $andam_loc_1 = DB::connection('sqlsrv')->table('Andamento_Processo');
            $andam_loc_1->select('CodigoProcesso','DataHoraEvento','Descricao');
            $andam_loc_1->where('TipoAndamentoProcesso','like','%ENVIADO PARA LOCALIZA%');
            $andam_loc_1->whereIn('CodigoProcesso',$codigoProcesso);

            $andam_loc_2 = DB::connection('sqlsrv')->table('Andamento_Processo_Historico');
            $andam_loc_2->select('CodigoProcesso','DataHoraEvento','Descricao');
            $andam_loc_2->where('TipoAndamentoProcesso','like','%ENVIADO PARA LOCALIZA%');
            $andam_loc_2->whereIn('CodigoProcesso',$codigoProcesso);
            $andam_loc_2->unionAll($andam_loc_1);
            $andam_loc_2->orderBy('DataHoraEvento','desc');

            //dd($andam_loc_2->get());

            $reponse = (new ProcessosGrid()) // you can then use it as $this->user within the class. It's set implicitly using the __set() call
            ->create([
                'query' => $query,
                'coluns' => $coluns,
                'localizadores' => $andam_loc_2->get(),
                'request' => $this->request
            ])
            ->renderOn('processos_grid');


            //dd( ($reponse->grid->getData()));
            return $reponse;

        }else{
            return true;
        }
    }

    public function carteira($carteira)
    {
        $query = DB::table('bank_name')
            ->where('is_active',1)
            ->orderBy('bank_name','asc')
            ->get();
        $this->sendDataToView('carteira', $query);
        $this->sendDataToView('planilha', $carteira);
        $this->sendDataToView('planilha', $carteira);

        return view('carteira')->with($this->sendToView);
    }

    protected function purse($bank)
    {
        $return = [];
        if(isset($bank)){
            $data = DB::table('bank_name')
                ->leftjoin('bank_data','bank_data.name_id','=','bank_name.id')
                ->where('bank_code',$bank)
                ->get();

            foreach ($data as $value){
                $return[] = $value->data_cod;
            }

        }else{
            $return = null;
        }

        return $return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $modal = [
            'model' => class_basename(Processos::class),
            'route' => route('processos.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('processos_modal', compact('modal'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Processos  $processos
     * @return \Illuminate\Http\Response
     */
    public function show( Processos $processos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Processos  $processos
     * @return \Illuminate\Http\Response
     */
    public function edit(Processos $processos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Processos  $processos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Processos $processos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Processos  $processos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Processos $processos)
    {
        //
    }
}
