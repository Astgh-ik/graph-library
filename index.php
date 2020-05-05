<?php 
class Graph{
	public	$nodes = [];
	public $edges = [];
	public function add_node($node){
		array_push($this->nodes,$node);
	}
	public function add_nodes($nodes){
		for($i = 0;$i < count($nodes);$i++){
			array_push($this->nodes,$nodes[$i]);
		}
	}
	public function add_edge(array $edge){
		array_push($this->edges,$edge);
	}
	public function add_edges($edges){
		for($i = 0;$i < count($edges); $i++){
			array_push($this->edges,$edges[$i]);
		}
	}
	public function degree($node=0){//գագաթի աստիճան նշանակում է կից կողերի քանակը
		$degree = 0;
		if (in_array($node, $this->nodes)) {
			for($i = 0;$i < count($this->edges);$i++){
				if (in_array($node,$this->edges[$i])) {//գտնում ենք կից եզրերը
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
			print "node ".$n." degree ".$this->degree();
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
	public function subgraph($nbunch){
		$graph = new Graph();
		$graph->nodes = $this->nodes;
		return $graph; 
	}
	public function edge_subgraph($edges){
		$graph = new Graph();
		$graph->edges = $edges;
		$graph->nodes = $this->nodes;
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
				for($j = 0;$j < 2;$j++){
					if ($this->edges[$i][$j] != $n) {
						array_push($arr,$this->edges[$i][$j]);
					}
				}
			}
		}
		return $arr;
	}
	public function non_neighbors($n){
		$arr = [];
		$node = $this->nodes;
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($n,$this->edges[$i])) {
				for($j = 0;$j < 2;$j++){
					if ($this->edges[$i][$j] != $n) {
						array_push($arr,$this->edges[$i][$j]);
					}
				}
			}
		}
		for($i = 0;$i < count($arr);$i++){
			for($j = 0;$j < count($node);$j++){
				if ($arr[$i] == $node[$j]) {
					array_splice($node,$j,1);
				}
			}
		}
		return $node;
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
		$middle = $nodes_for_star[0];
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
		for($i = 0;$i < count($nodes_for_path)-1;$i++){
			if (!in_array($nodes_for_path[$i], $this->nodes)) {
				array_push($this->nodes,$nodes_for_path[$i]);
			}
			$arr = [];
			array_push($arr,$nodes_for_path[$i]);
			array_push($arr,$nodes_for_path[$i+1]);
		}
	}
	public function induced_subgraph($nbunch){
		$sub = new Graph();
		$sub->nodes = $nbunch;
		$edge = [];
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($this->edges[$i][0], $nbunch) && in_array($this->edges[$i][1], $nbunch)) {
				//գտնում է այն եզրերը որոնք կազմված են տրված հանգույցներով
				array_push($edge,$this->edges[$i]);
			}
		}
		$sub->edges = $edge;
		return $sub;
	}
	public function remove_node($node){
		if (!in_array($this->nodes, $node)) {
			print_r('Նշված գագաթը չկա գրաֆում');
		}
		for($i = 0;$i<count($this->nodes);$i++){
			if ($this->nodes[$i]==$node) {
				array_splice($this->nodes,$i, 1);
			}
		}
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($node, $this->edges[$i])) {
				//հեռացնում է այն եզրերը որոնք կազմված էին տրված հանգույցով
				array_splice($this->edges,$i,1);
				$i--;
			}
		}
	}
	public function remove_edge($u,$v){
		for($i = 0;$i < count($this->edges);$i++){
			if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
				array_splice($this->edges,$i,1);
			}
		}
	}
	public function clear(){
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
		for($i=0;$i<count($this->edges);$i++){
			if (in_array($u, $this->edges[$i]) && in_array($v, $this->edges[$i])) {
				return true;
			}
		}
		return false;
	}
	public function min_weighted_vertex_cover(){
		$arr = [];
		foreach ($this->edges as $key) {
			if (!(in_array($key[1], $arr))) {
				$n = $this->neighbors($key[1]);
				$c = 0;
				for($i = 0;$i < count($n);$i++){
					if (in_array($n[$i], $arr)) {
						$c++;
					}
				}
				if ($c == 0) {
					array_push($arr, $key[1]);
				}
				else{
					if (!(in_array($key[1],$arr)||in_array($key[0],$arr))) {
						if($c < count($n)){
							array_push($arr,$key[1]);
						}
						else{
							array_push($arr,$key[0]);
						}
					}
				}
			}
		}
		return $arr;
	}
	public function independent_set(){
		$arr = [];
		foreach ($this->edges as $key) {
			if (!(in_array($key[1], $arr))) {
				$n = $this->neighbors($key[1]);
				$c = 0;
				for($i = 0;$i < count($n);$i++){
					if (in_array($n[$i], $arr)) {
						$c++;
					}
				}
				if ($c == 0) {
					array_push($arr, $key[1]);
				}
			}
		}
		return $arr;
	}
}
$G = new Graph();
$G->add_nodes([1,2,3,4,5,6]);
$G->add_edges([[1,2],[2,3],[1,3],[3,4],[1,4],[2,4],[1,5],[5,6]]);
// $g = $G->number_of_edges();
$g=$G->min_mximal_matching();
// $G->min_weighted_vertex_cover();
print_r($g);
?>