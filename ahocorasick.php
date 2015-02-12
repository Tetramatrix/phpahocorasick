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
        $this->queue[]=$this->head;
        $this->queue[]=$this->head;
        $this->queue[]=$this->head;
        
        while (count($this->queue)>=3)
        {
            for ($i=0;$i<3;$i++)
            {
                $fail=array_shift($this->queue);    
      
                if ($fail->left)
                {
                    array_unshift($this->queue,$fail->left);
                }
                
                if ($fail->mid)
                {
                    array_unshift($this->queue,$fail->mid);
                }
                
                if ($fail->right)
                {
                    array_unshift($this->queue,$fail->right);
                }
                
                if ($fail->fail==null && $fail!=$this->head)
                {
                    $fail->fail=$this->head;
                }
                
                if ($fail->pid->fail)
                {
                    $pid=$fail->pid->fail;
                    while ($fail->char!=$pid->char && $pid!=$this->head && $pid!=null)
                    {
                        $pid=$pid->pid->fail;
                    }
                    if ($pid==0)
                    {
                        $pid=$this->head;                
                    }
                    
                    switch ($fail->char)
                    {
                        case $pid->char:
                            $fail->fail=$pid->mid;      
                        break;
                        case $pid->left->char:
                            $fail->fail=$pid->left;
                        break;
                        case $pid->right->char:
                            $fail->fail=$pid->right;
                        break;
                        case $pid->left->left->char:
                            $fail->fail=$pid->left->left;
                        break;
                        case $pid->left->mid->char:
                            $fail->fail=$pid->left->mid;
                        break;
                        case $pid->left->right->char:
                            $fail->fail=$pid->left->right;
                        break;
                        case $pid->right->left->char:
                            $fail->fail=$pid->right->left;
                        break;
                        case $pid->right->left->char:
                            $fail->fail=$pid->right->left;
                        break;
                        case $pid->right->mid->char:
                            $fail->fail=$pid->right->mid;
                        break;
                        case $pid->right->right->char:
                            $fail->fail=$pid->right->right;
                        break;
                    }
                }
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
    function dictsuffix (&$n, $pos)
    {
        if (!is_object($n)) 
            return EMPTY_NODE;
        
        if ($n->is_leaf==false)
        {
            $this->result[$pos][$n->c]=$n->word->get();
        }
        if ($n->pid->is_leaf==false && $n->pid->c!=0 && $n->pid->c!=$n->c)
        {
            $n=$this->dictsuffix($n->pid,$pos);
        }
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
                    case (is_object($n->fail) && $ishead && $iscopy):
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
                    case (is_object($n->fail) && $ishead && $iscopy) :
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
                        if (is_object($n->fail) &&
                                $n->fail->is_leaf==false && $iscopy)
                        {
                            $this->dictsuffix($n->fail, $pos, $c);
                            
                        } else if (is_object($n->fail->pid) && $isfailpid)
                        {
                            $this->dictsuffix($n->fail->pid,$pos, $c);
                       
                        } else if (is_object($n->pid->fail) &&
                            $n->pid->fail->c!=0 &&
                                $n->pid->fail->c!=$n->c)
                        {
                            $this->dictsuffix($n->pid->fail,$pos, $c);
                        }
                        $this->result[$pos][$n->c]=$n->word->get();
                    }
                    break;    
                    case ($n->is_leaf==false && $n!= $this->head):
                    {
                        if (is_object($n->mid) &&
                            $n->mid->char==$next) 
                        {
                            $posm=$this->find($n->mid, $key, $len, $pos+1, $c);
                        }
                        if (is_object($n->fail) && $ishead && $iscopy &&
                                $n->fail->mid->char!=$next
                            )
                        {
                            $pos=$this->find($n->fail, $key, $len, $pos+1, $c);    
                        
                        } else if (is_object($n->fail->mid) &&
                            $n->fail->mid->char==$next &&
                                $n->fail->mid->c!=$n->c
                            )
                        {
                            $this->find($n->fail->mid, $key, $len, $pos+1, $c);
                            
                        } else if (is_object($n->fail->mid->right) &&
                            $n->fail->mid->right->char==$next &&
                                $n->fail->mid->right->c!=$n->c
                            )
                        {
                            $this->find($n->fail->mid->right, $key, $len, $pos+1, $c);
                        
                        } else if (is_object($n->fail->mid->left) &&
                            $n->fail->mid->left->char==$next &&
                                $n->fail->mid->left->c!=$n->c
                            )
                        {
                            $this->find($n->fail->mid->left, $key, $len, $pos+1, $c);
                        }
                        
                        if ($n->fail->is_leaf==false && $iscopy && ($pos+2)<$len)
                        {
                            $this->dictsuffix($n->fail,$pos);
                            
                        } else if (is_object($n->fail->pid) && $isfailpid)
                        {
                            $this->dictsuffix($n->fail->pid,$pos);
                        }
                        $this->result[$pos][$n->c]=$n->word->get();
                    }
                    break;
                    case ($n->is_leaf==true || $n==$this->head): 
                    {
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
                        } else if ($ishead)
                        {
                            $this->find($n->fail, $key, $len, $pos+1, $c);  
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
        $this->c=$c;
    }
    
    public function match($key)
    {
        $this->head->fail=0;
        $this->makefailure();
       
        for ($i=0,$len=strlen($key);$i<$len;$i++) 
        {
            $char=$this->find($this->head, $key ,strlen($key), $i, $char);
            if ($char==$i) $char++;
            $i=($char-1);
        }
        ksort($this->result);
        foreach ($this->result as $k1=>$v1) {
            ksort($v1);
            foreach ($v1 as $k2=>$v2) {
                $tmp[]=$v2;
            }
        }
        return implode(",",$tmp);
    }
}
?>