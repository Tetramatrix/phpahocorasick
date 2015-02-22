<?php
/***************************************************************
*
*  (c) 2015 Chi Hoang (info@chihoang.de)
*  All rights reserved
*  
***************************************************************/
namespace Ahocorasick;

define ( "EMPTY_NODE", "0" );    
define ( "START_CHAR_COUNT","0" );

class Trie {

    var $head;   
    var $c=0;
    var $pattern=0;
    var $result;
    
    public function makefailure()
    {
        $visited=array();
        $this->queue[]=$this->head;
        $visited[$this->head->c]=true;
        
        while (count($this->queue)>0 && count($visited)!=$this->head->c)
        {
            $cur=array_shift($this->queue);    
            list($arr2,$arr3)=$this->queue;
            
            if ($cur->pid->fail || $cur->pid)
            {
                if (!is_object($cur->fail)) {
                    $cur->fail=$this->head;
                }
                if (is_object($cur->right) && !is_object($cur->right->fail)) {
                    $cur->right->fail=$this->head;
                }
                if (is_object($cur->left) && !is_object($cur->left->fail)) {
                    $cur->left->fail=$this->head;
                }
                if (is_object($cur->mid) && !is_object($cur->mid->fail)) {
                    $cur->mid->fail=$this->head;
                }
                $a=$b=$c=false;
                if ($cur->left && !$visited[$cur->left->c]) {
                    $a=$visited[$cur->left->c]=true;
                    array_push($this->queue,$cur->left);
                }
                if ($cur->mid && !$visited[$cur->mid->c]) {
                    $a=$visited[$cur->mid->c]=true;
                    array_push($this->queue,$cur->mid);
                }
                if ($cur->right && !$visited[$cur->right->c]) {
                    $a=$visited[$cur->right->c]=true;
                    array_push($this->queue,$cur->right);
                }
                if ($arr2->left && !$visited[$arr2->left->c]) {
                    $b=$visited[$arr2->left->c]=true;
                    array_push($this->queue,$arr2->left);
                }
                if ($arr2->mid && !$visited[$arr2->mid->c]) {
                    $b=$visited[$arr2->mid->c]=true;
                    array_push($this->queue,$arr2->mid);
                }
                if ($arr2->right && !$visited[$arr2->right->c]) {
                    $b=$visited[$arr2->right->c]=true;
                    array_push($this->queue,$arr2->right);
                }
                if ($arr3->left && !$visited[$arr3->left->c]) {
                    $c=$visited[$arr3->left->c]=true;
                    array_push($this->queue,$arr3->left);
                }
                if ($arr3->mid && !$visited[$arr3->mid->c]) {
                    $c=$visited[$arr3->mid->c]=true;
                    array_push($this->queue,$arr3->mid);
                }
                if ($arr3->right && !$visited[$arr3->right->c]) {
                    $c=$visited[$arr3->right->c]=true;
                    array_push($this->queue,$arr3->right);
                }   
                
                $a=false;
                foreach (array_reverse($this->queue) as $k=>$v)
                {
                    if ($cur->char==$v->char && $cur->c!=$v->c)
                    {    
                        if ($cur->pid->char==$v->pid->char || $v->c==0)
                        {
                            if (is_object($v->fail) && $v->fail->c==0) {
                                $cur->fail=$v;
                                $a=true;
                            } else if (!is_object($v->fail)) {
                                $cur->fail=$v;
                                $a=true;
                            }
                        }
                    }
                }
                if ($a==false) {
                    $cur->fail=$this->head;
                }
                
            } else
            {
                if ($cur->left) {
                    $cur->left->fail=$this->head;
                    array_push($this->queue,$cur->left);
                }
                if ($cur->mid){
                    $cur->mid->fail=$this->head;
                    array_push($this->queue,$cur->mid);
                }
                if ($cur->right) {
                    $cur->right->fail=$this->head;
                    array_push($this->queue,$cur->right);
                }
                if ($cur->fail==null && $cur!=$this->head) $cur->fail=$this->head;    
            }
        }
    }

    function insert(&$n, $key, $len, $pos, $is_leaf=false, $c=0, &$pid=EMPTY_NODE)
    {
        $a=false;
        if (!is_object($n))
        {
            $n = new Node($key->payload[$pos], $c, $pid);
            $n->c=$c;
            $a=true;
        }  
        
        if (ord($key->payload[$pos]) < ord($n->char))
        {        
            if ($is_leaf==false && $a==true) 
            {
                ++$c;
            } else if ($is_leaf==true)
            {
                $c=0;
            }
            $c=$this->insert($n->left, $key, $len, $pos, $is_leaf, $c, $n);    
        
        } else if (ord($key->payload[$pos]) > ord ($n->char))
        {
            if ($is_leaf==false && $a==true) 
            {
                ++$c;
            } else if ($is_leaf==true)
            {
                $c=0;
            }
            $c=$this->insert($n->right, $key, $len, $pos, $is_leaf, $c, $n);
            
        }  else if ($pos+1 == $len)
        {
            $n->word = $key;
            
            if ($is_leaf==false && $a==true)  
            {
                $n->is_leaf = false;
                $n->c=$c;
                ++$c;
                
            } else if ($is_leaf==false)
            {
                $n->is_leaf=false;
                $n->c=$c;
            }
            
        } else {
            
            if ($is_leaf==false && $a==true) 
            {
                ++$c;
            } else if ($is_leaf==true)
            {
                $c=0;
            }
            $c=$this->insert($n->mid, $key, $len, $pos+1, $is_leaf, $c, $n);
        }
        return $c;     
    }
 
    //////////////////////////////////////////////////////////////////////////
    function find (&$n, $key, $len, $pos=START_CHAR_COUNT, $c=0)
    {
        if (!is_object($n)) 
            return EMPTY_NODE;

        $next=substr($key,$pos+1,1);
        $prev=substr($key,$c,1);
        $iscopy=($n->fail->c!=$n->c) ? true : false;
        $ishead=($n->fail->c!=0) ? true : false;
        $isfailpid=($n->fail->pid->c!=$n->c) ? true : false;
        
        switch (true)
        {
            case (ord($key[$pos]) < ord($n->char)):
                
                switch (true)
                {
                    case (is_object($n->mid->left) &&
                    $n->mid->left->char==$next &&
                            $n->char==$prev &&
                       $n->mid->left->c!=$n->c
                    ) :
                    {
                        $this->find($n->mid->left, $key, $len, $pos+1, $c);
                    }
                    break;
                    case (is_object($n->left)) : 
                    {
                        $pos=$this->find($n->left, $key, $len, $pos, $c);
                    }
                    break;
                    case (is_object($n->fail) && $ishead && $iscopy && $n->fail->pic->char==$prev):
                    {
                        $pos=$this->find($n->fail, $key, $len, $pos+1, $c); 
                    } 
                    break;    
                }
            case (ord($key[$pos]) > ord($n->char)):
                
                switch (true)
                {
                    case (is_object($n->mid->right) &&
                        $n->mid->right->char==$next &&
                           $n->char==$prev && 
                            $n->mid->right->c != $n->c
                        ) :
                    {
                        $this->find($n->mid->right, $key, $len, $pos+1, $c);
                    
                    }
                    break;
                    case (is_object($n->right)) :
                    {                               
                        $pos=$this->find($n->right, $key, $len, $pos, $c);
                    }
                    break;
                    case (is_object($n->fail) && $ishead && $iscopy && $n->fail->pic->char==$prev) :
                    {
                        $pos=$this->find($n->fail, $key, $len, $pos+1, $c); 
                    }
                    break;
                }
            break;
            default:
                switch (true)
                {
                    case ($pos+1==$len && $n->is_leaf==false):
                    {
                        $this->result[$pos][$n->c]=$n->word->get();
                    }
                    break;    
                    case ($n->is_leaf==false && ($n!= $this->head || is_object($n->word))):
                    {
                        if (is_object($n->mid) &&
                            $n->mid->char==$next) 
                        {
                            $this->find($n->mid, $key, $len, $pos+1, $c);
                        }
                        
                        $this->result[$pos][$n->c]=$n->word->get();
                        
                    }
                    break;
                    case ($n->is_leaf==true || $n==$this->head): 
                    {
                        if (is_object($n->fail->mid) &&
                            $n->fail->mid->char==$next &&
                                $n->fail->mid->c!=$n->c && $n->fail->char==$n->char
                            )
                        {
                            $this->find($n->fail->mid, $key, $len, $pos+1, $c);
                        }
                        if (is_object($n->mid) && $n->mid->char==$next) 
                        {
                            $pos=$this->find($n->mid, $key, $len, $pos+1, $c);
                            
                        } else if (is_object($n->mid->right) &&
                            $n->mid->right->char==$next &&
                                $n->mid->right->c!=$n->c
                            )
                        {
                            $this->find($n->mid->right, $key, $len, $pos+1, $c);
                        
                        } else if (is_object($n->mid->left) &&
                            $n->mid->left->char==$next &&
                                $n->mid->left->c!=$n->c
                            )
                        {
                            $this->find($n->mid->left, $key, $len, $pos+1, $c);
                        }
                    }
                break;   
                }
            break;
        }
        return $pos;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
class Payload 
{
    var $payload;
    
    public function __construct($string)
    {
        $this->payload = $string;
    }
    
    public function get()
    {
        return $this->payload;
    }
    
    public function error ()
    {
        return "Not Found!\n";
    }
};

///////////////////////////////////////////////////////////////////////////////////////////
class Node 
{
    var $is_leaf,$left, $mid, $right, $word, $char, $c, $fail, $pid;
            
    public function __construct ($char=null, $c=0, $pid=EMPTY_NODE)
    { 
        if  ($char==null)
        {
            $this->left = EMPTY_NODE; 
            $this->right = EMPTY_NODE;
            $this->mid = EMPTY_NODE;
            $this->is_leaf = false;
            $this->word = $this->char = "";
            $this->c = $c;
            $this->fail = EMPTY_NODE;
            $this->pid = EMPTY_NODE;
        
        } else
        {
            $this->char = $char; 
            $this->is_leaf = true;
            $this->pid = $pid;
        }
    }
    
    public function __unset ( $name )
    {
        echo "$name";
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
class Ahocorasick extends Trie
{
    var $keywords;
    
    public function __construct()
    {
        $this->queue=$this->result=array();
    }
     
    public function add($key)
    {
        $str=new Payload($key);
        $c=$this->insert($this->head, $str,strlen($str->payload),START_CHAR_COUNT,0,$this->c);        
        if ($this->c==$c) $c+=strlen($str->payload);
        $this->c=$c;
    }
    
    public function match($key,$wildcard=null)
    {
        if ($wildcard)
        {
            $arr=explode("*",$wildcard);
            foreach (array_reverse($arr) as $value)
            {
                $str=new Payload($value);
                $c=$this->insert($this->head, $str,strlen($str->payload),START_CHAR_COUNT,0,$this->c);
                if ($this->c==$c) $c+=strlen($str->payload);
                $this->c=$c;
            }        
        }
        
        $this->head->fail=0;    
        $this->makefailure();
       
        for ($i=0,$len=strlen($key);$i<$len;$i++) 
        {
            $char=$this->find($this->head, $key ,strlen($key), $i, $char);
            if ($char==$i) $char++;
            $i=($char-1);
        }
        ksort($this->result);
        foreach ($this->result as $k1=>$v1)
        {
            ksort($v1);
            foreach ($v1 as $k2=>$v2)
            {
                $tmp[]=$v2;
            }
        }
        
        if ($wildcard)
        {
            $a=array();
            $e=count($tmp);
            $d=count($arr);
            $x=0;
            
            foreach ($tmp as $v1)
            {
                $z=$x;
                $c=0;
                $v=$b=array();

                for($i=$z;$i<$e;$i++)
                {
                    if ($d==$c && $i==$e)
                    {
                        break;
                    } else if ($d==$c && $tmp[$i-1]==$arr[$c-1])
                    {
                        $v[$i-1]=true;
                        $a[$x][$i]=implode(",",$b);
                        $c--;
                    } else if ($d==$c)
                    {
                        $v[$i-1]=true;
                        $c--;
                    }
                    if ($tmp[$i]==$arr[$c] && $v[$i]!=true)
                    {
                        if ($c==0) $z=$x=$i;
                        $c++;
                        $b[$i]=$tmp[$i];
                    } else if ($c>0)
                    {
                        $b[$i]=$tmp[$i];
                    } 
                }
                if ($d==$c)
                {
                    $v[$i-1]=true;
                    //$a[$x][$i]="[".implode(",",$b)."]";
                    $a[$x][$i]=implode(",",$b);
                    $b=array();
                    $c=0;
                    $z=$x;
                } 
                $x++;
            }
        }
        
        if ($wildcard)
        {
            $beta=explode("*",$wildcard);          
            $x=$s=$p=0;
            foreach ($a as $k => $v)
            {
                $delta=$p=$k*strlen($beta[0]);
                foreach ($v as $ki => $vi)
                {
                    $g=array();
                    $e=$c=0;
                    $l=explode(",",$vi);
                    //$p=$k;
                    foreach ($l as $k2 => $v2)
                    {
                        if ($p<strlen($key))
                        {
                            $e=strpos($key,$v2,$p);
                            if ($e===false) break;
                            if ($c==0)
                            {
                                $p=0;
                                $s=$e;  
                            }
                            $p=($e+$delta-strlen($v2))-$p+$c;
                            if ($g[$p]==false)
                            {
                                $g[$p]=true;
                            } else
                            {
                                $p=$e-1;
                            }
                            if ($p<0) $p=0;
                            $c++;
                        } else
                        {
                            $e=false;
                        }
                    }
                    if ($e!==false)
                    {
                        $b[]=substr($key,$s,$e-$s+strlen($v2));
                        //$p=$delta;
                    }
                }
            }
        }
        
        return implode(",",$wildcard==null ? $tmp : $b);
    }
}
?>