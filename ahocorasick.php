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
    var $v=0;
    
    function insert (&$n, $key, $pos, $is_leaf=false, $c=0, &$pid=EMPTY_NODE)
    {
        $a=false;
        if (!is_object($n))
        {
            $n = new Node ($key->payload[$pos], $c, $pid);
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
            
            $c=$this->insert($n->left, $key, $pos, $is_leaf, $c, $n);    
        
        } else if (ord($key->payload[$pos]) > ord ($n->char))
        {
            if ($is_leaf==false && $a==true) 
            {
                ++$c;
            } else if ($is_leaf==true)
            {
                $c=0;
            }
            
            $c=$this->insert($n->right, $key, $pos, $is_leaf, $c, $n);
            
        }  else {
        
            if ( $pos+1 == strlen($key->payload))
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
                
                if ($is_leaf==false && $a==true ) 
                {
                    ++$c;
                } else if ($is_leaf==true)
                {
                    $c=0;
                }
                $c=$this->insert($n->mid, $key, $pos+1, $is_leaf, $c, $n);
            }
        }
        return $c;     
    }

    //////////////////////////////////////////////////////////////////////////
    function fail (&$n)
    {
        if (!is_object($n)) 
            return EMPTY_NODE;
        
        if ($n->is_leaf==false)
        {
            $this->result[]=$n->word->get();
        }
        if ($n->fail->pid && $n->pid->fail!=$this->head && $n->pid->fail->c!=$n->c)
        {
            $n=$this->fail($n->fail->pid);
        }
    }
 
    //////////////////////////////////////////////////////////////////////////
    function search (&$n, $key, $pos)
    {
        if (!is_object($n)) 
            return EMPTY_NODE;

        if (ord ($key[$pos]) < ord($n->char))
        {
            if (is_object($n->left)) 
            {
                $this->search ($n->left, $key, $pos);
                
            } else if (is_object($n->mid) && $n->mid->pid->char==substr($key,$pos-1,1))
            {
                $this->search ($n->mid, $key, $pos);
                
            } else if (is_object($n->fail) && $n->fail->char==substr($key,$pos+1,1))
            {
                $this->search ($n->fail, $key, $pos+1); 
            } else if ($pos+1 <= strlen($key))
            {
                $this->search ($this->head, $key, $pos+1); 
            }
        }
        else if (ord($key[$pos]) > ord($n->char))
        {
            if (is_object($n->right)) 
            {                               
                $this->search ($n->right, $key, $pos);
                
            }  else if (is_object($n->mid) && $n->mid->pid->char==substr($key,$pos-1,1))
            {
                $this->search ($n->mid, $key, $pos);
            
            } else if (is_object($n->fail) && $n->fail->char==substr($key,$pos+1,1))
            {
                $this->search ($n->fail, $key, $pos+1); 
            
            } else if ($pos+1 <= strlen($key))
            {
                $this->search ($this->head, $key, $pos+1);
            }
        } else 
        {
            if ($pos+1==strlen($key) && $n->is_leaf==false)
            {
                if (is_object($n->fail) && $n->fail->is_leaf==false && $n->fail->c!=$n->c)
                {
                    $this->fail($n->fail);
                }
                if (is_object($n->fail->pid) && $n->fail->pid->c!=$n->c)
                {
                    $this->fail($n->fail->pid);
                } else  if (is_object($n->pid->fail) &&
                    $n->pid->fail!=$this->head &&
                        $n->pid->fail->c!=$n->c)
                {
                    $this->fail($n->pid->fail);
                }
                $this->result[]=$n->word->get();
                
            } else if ($n->is_leaf==false && $n != $this->head)
            {
                if (is_object($n->fail->mid) &&
                    $n->fail->mid->char==substr($key,$pos+1,1) &&
                        $n->fail->mid->c != $n->c
                            && $n->is_leaf==true
                    )
                {
                    $this->search($n->fail->mid, $key, $pos+1, $pos+1);
                
                } else if (is_object($n->mid) &&
                    $n->mid->char==substr($key,$pos+1,1) &&
                        $n->mid->c != $n->c
                    )
                {
                    $this->search( $n->mid, $key, $pos+1);
                    
                } else if (is_object($n->fail->mid->right) &&
                    $n->fail->mid->right->char==substr($key,$pos+1,1) &&
                        $n->fail->mid->right->c != $n->c
                    )
                {
                    $this->search($n->fail->mid->right, $key, $pos+1, $pos);
                
                } else if (is_object($n->fail->mid->left) &&
                    $n->fail->mid->left->char==substr($key,$pos+1,1) &&
                        $n->fail->mid->left->c != $n->c
                    )
                {
                    $this->search ($n->fail->mid->left, $key, $pos+1);
                
                } else if (is_object($n->fail->right->mid->right) &&
                    $n->fail->right->mid->right->char==substr($key,$pos+1,1) &&
                        $n->fail->right->mid->right->c != $n->c
                    )
                {
                    $this->search($n->fail->right->mid->right, $key, $pos+1);
                    
                } else if (is_object($n->fail->right->mid->left) &&
                    $n->fail->right->mid->left->char==substr($key,$pos+1,1) &&
                        $n->fail->right->mid->left->c != $n->c
                    )
                {
                    $this->search($n->fail->right->mid->left, $key, $pos+1);    
                    
                } else if (is_object($n->fail->left->mid->right) &&
                    $n->fail->left->mid->right->char==substr($key,$pos+1,1) &&
                        $n->fail->left->mid->right->c != $n->c
                    )
                {
                    $this->search($n->fail->left->mid->right, $key, $pos+1);    
                
                } else if (is_object($n->fail->left->mid->left) &&
                    $n->fail->left->mid->left->char==substr($key,$pos+1,1) &&
                        $n->fail->left->mid->left->c != $n->c
                    )
                {
                    $this->search($n->fail->left->mid->left, $key, $pos+1);
                    
                }  else if (is_object($n->fail->pid->left) &&
                    $n->fail->pid->left->char==substr($key,$pos+1,1) &&
                        $n->fail->pid->left->c != $n->c
                    )
                {
                    $this->search($n->fail->pid->left, $key, $pos+1);
                    
                }  else if (is_object($n->fail->pid->right) &&
                    $n->fail->pid->right->char==substr($key,$pos+1,1) &&
                        $n->fail->pid->right->c != $n->c
                    )
                {
                    $this->search($n->fail->pid->right, $key, $pos+1);   
               
                } else if (is_object($n->fail) &&
                    $n->fail->char==substr($key,$pos+1,1) &&
                        $n->fail->c != $n->c
                    )
                 {
                    $this->search($n->fail, $key, $pos+1);
                
                } else if ($n->fail->c != $n->c)
                {
                    $this->search($n->fail, $key, $pos+1);
                    
                } else  if ($pos+1 <= strlen($key))
                {
                    $this->search($this->head,$key,$pos+1);
                }
                if ($n->fail->is_leaf==false && $n->fail->c!=$n->c)
                {
                    $this->fail($n->fail);
                }
                if (is_object($n->fail->pid) && $n->fail->pid->c!=$n->c)
                {
                    $this->fail($n->fail->pid);
                } else if (is_object($n->pid->fail) &&
                    $n->pid->fail!=$this->head &&
                        $n->pid->fail->c!=$n->c)
                {
                    $this->fail($n->pid->fail);
                }
                $this->result[]=$n->word->get();
                
            } else if ($n->is_leaf==true || $n==$this->head) 
            {
                if (is_object($n->mid) && $n->mid->char==substr($key,$pos+1,1)) 
                {
                    $this->search($n->mid, $key, $pos+1);
                    
                } else if ($n->fail==$this->head)
                {
                    $this->search($n->fail, $key, $pos+1);
                    
                } else if ($n->fail->c!=$n->c)
                {
                    $this->search($n->fail, $key, $pos+1);
                } else if ($pos+1 <= strlen($key))
                {
                    $this->search($this->head, $key, $pos+1); 
                }
            } else
            {
                $this->search ($this->head, $key, $pos); 
            }
        }
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
    
    public function get ()
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
        if  ( $char == null )
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
    
    public function add ( $key )
    {
        $c=$this->insert ( $this->head, new Payload ($key), START_CHAR_COUNT,0,$this->c );        
        $this->c=$c;
    }
    
    public function makefail()
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
                    if ($fail->char==$pid->char)
                    {
                        $fail->fail=$pid->mid;    
                    } else if ($fail->char==$pid->left->char)
                    {
                        $fail->fail=$pid->left;
                    }  else if ($fail->char==$pid->right->char)
                    {
                        $fail->fail=$pid->right;
                    } else if ($fail->char==$pid->left->left->char)
                    {
                        $fail->fail=$pid->left->left;
                    } else if ($fail->char==$pid->left->mid->char)
                    {
                        $fail->fail=$pid->left->mid;
                    } else if ($fail->char==$pid->left->right->char)
                    {
                        $fail->fail=$pid->left->right;
                    }else if ($fail->char==$pid->right->left->char)
                    {
                        $fail->fail=$pid->right->left;
                    } else if ($fail->char==$pid->right->mid->char)
                    {
                        $fail->fail=$pid->right->mid;
                    }  else if ($fail->char==$pid->right->right->char)
                    {
                        $fail->fail=$pid->right->right;
                    }
                }
            }
        }
    }

    public function match ( $key )
    {
       $this->head->fail=0;
       $this->makefail();
       $result = $this->search ( $this->head, $key , START_CHAR_COUNT );
       return implode(",",$this->result);
    }
}
?>