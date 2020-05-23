<?php 
class Graph{
	public	$nodes = [];
	public $edges = [];
	public $frozen=false;
	public function add_node($node){
		if ($this->frozen) {
			return $this;
		}
		array_push($this->nodes,$node);
	}
	public function add_nodes($nodes){
		if ($this->frozen) {
			return $this;
		}
		for($i = 0;$i < count($nodes);$i++){
			array_push($this->nodes,$nodes[$i]);
		}
	}
	public function add_edge(array $edge){
		if ($this->frozen) {
			return $this;
		}
		array_push($this->edges,$edge);
		if (!in_array( $edge[0],$this->nodes)) {
			array_push($this->nodes, $edge[0]);
		}
		else if(!in_array($edge[1],$this->nodes)){
			array_push($this->nodes, $edge[1]);
		}
	}
	public function add_edges($edges){
		if ($this->frozen) {
			return $this;
		}
		for($i = 0;$i < count($edges); $i++){
			array_push($this->edges,$edges[$i]);
		if (!in_array( $edges[$i][0],$this->nodes)) {
			array_push($this->nodes, $edges[$i][0]);
			}
		else if(!in_array($edges[$i][1],$this->nodes)){
			array_push($this->nodes, $edges[$i][1]);
			}
		}
	}
	public function freeze(){
		$this->frozen = true;
	}
	public function unfrozen(){
		$this->frozen = false;
	}
	public function is_frozen(){
		return $this->frozen;
	}
	public function degree($node=0){//գագաթի աստիճան նշանակում է կից կողերի քանակը
		$degree = 0;
		if (in_array($node, $this->nodes)) {
			for($i = 0;$i < count($this->edges);$i++){
				if (in_array($node,$this->edges[$i])) {//գտնում ենք կից կողերը
					$degree++;
				}
			}
		}
		else{
			for($i=0;$i<count($this->nodes);$i++){
				for($j=0;$j<count($this->edges);$j++){
					if (in_array($this->nodes[$i],$this->edges[$j])) {
						$degree++;
					}
				}
			}
		}
		return $degree;
	}
	public function density(){
		$node=count($this->nodes);
		$edge = count($this->edges);
		$density = (2*$edge)/($node*($node-1));//գրաֆի խտության բանաձև
		return $density;
	}
	public function info($n=0){
		if ($n==0) {
			print "nodes ".count($this->nodes)." edges ".count($this->edges); 
		}
		if($n){
			print "node ".$n." degree ".$this->degree($n);
		}
	}
	public function create_empty_copy(){
		$g = new Graph();
		$g->nodes = $this->nodes;
		$g->edges = [];
		return $g;
	}
	public function is_empty(){
		if (count($this->edges) == 0) {
			return true;
		}
		return false;
	}
	public function subgraph($nbunch=[]){
		$graph = new Graph();
		if ($nbunch) {
			$graph->nodes = $nbunch;
		}
		else{
			$graph->nodes = $this->nodes;
		}
		return $graph; 
	}
	public function edge_subgraph($edges){
		$graph = new Graph();
		$graph->add_edges($edges);
		return $graph; 
	}
	public function nodes(){
		return $this->nodes;
	}
	public function number_of_nodes(){
		return count($this->nodes);
	}
	public function neighbors($n){
		$arr = [];
		for($i = 0;$i < count($this->edges);$i ++){
			if (in_array($n,$this->edges[$i])) {
				for($j = 0; $j < 2; $j++){
					if ($this->edges[$i][$j] != $n) {
						array_push($arr,$this->edges[$i][$j]);
					}
				}
			}
		}
		return $arr;
	}
	public function non_neighbors($n){
		$nbr=$this->neighbors($n);
		$arr=[];
		for($i=0;$i<count($this->nodes);$i++){
			if (!in_array($this->nodes[$i],$nbr) && $this->nodes[$i]!=$n) {
				array_push($arr,$this->nodes[$i]);
			}
		}
		return $arr;
	}
	public function common_neighbors($u,$v){
		$arr = [];
		$u_neighbors = $this->neighbors($u);
		$v_neighbors = $this->neighbors($v);
		for($i = 0;$i < count($u_neighbors);$i++){
			if (in_array($u_neighbors[$i], $v_neighbors)) {
				array_push($arr,$u_neighbors[$i]);
			}
		}
		return $arr;
	}
	public function edges(){
		return $this->edges;
	}
	public function number_of_edges(){
		return count($this->edges);
	}
	public function number_of_selfloops(){
		$count = 0;
		for($i = 0;$i < count($this->edges);$i++){
			if ($this->edges[$i][0] == $this->edges[$i][1]) {
				$count++;
			}
		}
		return $count;
	}
	public function nodes_with_selfloops(){
		$arr=[];
		for($i = 0;$i < count($this->edges);$i++){
			if ($this->edges[$i][0]==$this->edges[$i][1]) {
				array_push($arr,$this->edges[$i][1]);
			}
		}
		return $arr;
	}
	public function selfloop_edges(){
		$arr = [];
		for($i = 0;$i < count($this->edges);$i++){
			if ($this->edges[$i][0] == $this->edges[$i][1]){
				array_push($arr,$this->edges[$i]);
			}
		}
		return $arr;
	}
	public function add_star($nodes_for_star){
		if ($this->frozen) {
			return $this;
		}
		$middle = $nodes_for_star[0];
		array_push($this->nodes,$middle);
		for($i = 1;$i < count($nodes_for_star); $i++){
			if (!in_array($nodes_for_star[$i], $this->nodes)) {
				array_push($this->nodes,$nodes_for_star[$i]);
			}
			$arr = [$middle];
			array_push($arr,$nodes_for_star[$i]);//առաջին գագաթը միացնում է մնացածին
			array_push($this->edges,$arr);
		}
	}
	public function add_path($nodes_for_path){
		if ($this->frozen) {
			return $this;
		}
		for($i = 0;$i < count($nodes_for_path)-1;$i++){
			if (!in_array($nodes_for_path[$i], $this->nodes)) {
				array_push($this->nodes,$nodes_for_path[$i]);
			}
			$arr = [];
			array_push($arr,$nodes_for_path[$i]);
			array_push($arr,$nodes_for_path[$i+1]);
			array_push($this->edges, $arr);
		}
		array_push($this->nodes, $nodes_for_path[count($nodes_for_path)-1]);
	}
	public function induced_subgraph($nbunch){
		$sub = new Graph();
		$sub->nodes = $nbunch;
		$edge = [];
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($this->edges[$i][0], $nbunch) && in_array($this->edges[$i][1], $nbunch)) {
				//գտնում է այն կողերը որոնք կազմված են տրված գագաթներով
				array_push($edge,$this->edges[$i]);
			}
		}
		$sub->edges = $edge;
		return $sub;
	}
	public function remove_node($node){
		if ($this->frozen) {
			return $this;
		}
		if (!in_array($node,$this->nodes)) {
			print_r('Նշված գագաթը չկա գրաֆում');
		}
		for($i = 0;$i<count($this->nodes);$i++){
			if ($this->nodes[$i]==$node) {
				array_splice($this->nodes,$i, 1);
			}
		}
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($node, $this->edges[$i])) {
				//հեռացնում է այն կողերը որոնք կազմված էին տրված գագաթներով
				array_splice($this->edges,$i,1);
				$i--;
			}
		}
	}
	public function remove_nodes_from(array $nodes){
		if ($this->frozen) {
			return $this;
		}
		for($i = 0; $i < count($nodes); $i++){
			$this->remove_node($nodes[$i]);
		}
	}
	public function remove_edge($u,$v){
		if ($this->frozen) {
			return $this;
		}
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
				array_splice($this->edges,$i,1);
			}
		}
	}
	public function remove_edges(array $edges){
		if ($this->frozen) {
			return $this;
		}
		for($i=0;$i<count($edges); $i++){
			$this->remove_edge($edges[$i][0],$edges[$i][1]);
		}
	}
	public function clear(){
		if ($this->frozen) {
			return $this;
		}
		$this->nodes = [];
		$this->edges = [];
	}
	public function has_node($node){
		if (in_array($node, $this->nodes)) {
			return true;
		}
		return false;
	}
	public function has_edge($u,$v){
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
				return true;
			}
		}
		return false;
	}
	public function min_weighted_vertex_cover(){
		$cost = new stdClass();
		for($i = 0;$i < count($this->nodes);$i++){
			$key = $this->nodes[$i];
			$cost->$key = 1;
		}
		foreach ($this->edges as $key) {
			$u = $key[0];
			$v = $key[1];
			$min_cost = min($cost->$u, $cost->$v);
			$cost->$u -= $min_cost;
			$cost->$v -= $min_cost; 
		}
		$arr = [];
		foreach ($cost as $key => $value) {
			if ($value == 0) {
				array_push($arr,$key);
			}
		}
		return $arr;
	}
	
	public function Ramsay_R2(){
		$arr = [];
		if (count($this->nodes) < 1) {
				return [[],[]];
			}
			$n = rand(0,count($this->nodes)-1);
			//ընտրում ենք պատահական գագաթ
			$node = $this->nodes[$n];
			$nbrs = $this->neighbors($node);
			$nnbrs = $this->non_neighbors($node);
			$ra = $this->induced_subgraph($nbrs);
			$re = $this->induced_subgraph($nnbrs);
			$arr1 = $ra->Ramsay_R2();
			$c_1 = $arr1[0];
			$i_1 = $arr1[1];
			$arr2 = $re->Ramsay_R2();
			$c_2 = $arr2[0];
			$i_2 = $arr2[1];
			array_push($c_1,$node);
			array_push($i_2,$node);
			$array = [];
			if ($c_1 > $c_2) {
				array_push($array,$c_1);
			}
			else{
				array_push($array, $c_2);
			}
			if ($i_1>$i_2) {
				array_push($array,$i_1);
			}
			else{
				array_push($array,$i_2);
			}
			return $array;
	}
	public function maximum_independent_set(){
		$answer = $this->Ramsay_R2();
		return $answer[1];
	}
}
?>
