<?php

namespace App;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Collection;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use PhpParser\ErrorHandler\Collecting;
use PhpParser\Node\Expr\Cast\Object_;
use phpseclib\Crypt\Random;
use phpseclib\Crypt\RSA;

class DotLicense implements Jsonable, JsonSerializable
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    //protected $attributes = [];

    /**
     * The Public Keys.
     *
     * @var array(RSA)
     */
    private $PublicKeys;

    /**
     * The Private Keys.
     *
     * @var array(RSA)
     */
    private $PrivateKeys;

    /**
     * License Model.
     *
     * @var License
     */
    private $xLicense;

    /*
     * */
    public function __construct(License $_xLicense)
    {
        $this->xLicense = $_xLicense;
        $this->PublicKeys = collect(
            ["<RSAKeyValue><Modulus>0dtbkUTfa10XFaCZz79PtBT2LCpTCuCRcyaHeGmEO5d9D8xy8CvEpWluOROfOZwHSKBwbjJL2EKUvxhRZ/t8G2zBHXaeD9ZvyBaggMH7snPRPTqin9xM1nPmZfcf52hgsZYlnyR5z1FMq+G3q80x9iNxeJhCfm7EktDeUddgOtU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>xq8BnLwxBn30gr03JjYbD8y/ZLWrI/av/rgg3WMZHz+44IO2cCj5Rb/RUIZjpeCrUV/tTRhsYfp9kp7ODyyYTW8Tl6bfWbT+DBui/eZ8ZW+iWqXNNODAMBhQFO2oVYet6NYTiBrjmoLeTrF08kC005C6FNjrSFi6TAMClZ2kPsU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>ztKnEcp8IBMSMr/0+jKm80dUhK5ykkMRP/JUyYckeGFAt4B+RQwJbL2/eX2C7KaGj7BmBVfYvNJHKFIqLbwCakg5biShV4iEMh0iiuO7zD+sYTW9/rjZ2RLB0aYWKMg+wNXo2LpSy7ldrJZngsEDyjFKVFl28MbJ8Oc49B77/NU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>web4CXubIblHHuD/1AFpEBu23AyN8RtryNvpFeL5GP5NSCWF90RjqunlPEI4Yb6OAUJcGpRUO95KrsXIoJWl+2gaS57cz1nNwLOd9IVmg4SFkaOmgxqV0l5MVRrOSqyuiApkVepg+/Dgqd/i6Qk4qlTlY4EqfUVJI/J3/vUj9oU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>zvrdR9mZwKqVHmPZyqtBKSCxWyWABAB01aVTq7jgOVn0Xx47Vk4/68Eys/ucT8JqEWB3h1PTAqNnQAZ4jdX54RRnAhOOrvYdRr2VOl+HV05Tc12o7p9I+235nhjN7RquIUS0h+7FxwwtUAwAcPZoFbUWkQtJu612l3jVQAVn6Gk=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>sCZGipQlDY74f9lWcJYQ5Vy4SXsDbskaF/Vdl3eLdi39e1VJ4cZewobCZNnCQGmL4D9VHVu1Zk1EqVOgbGzS5hJbXP1o7AmRAsm3F93H/20Cu0oEdDZFLV/R3ZhEpQspNg9Ro4wk+4s/432I67itFVlUnRa7Fw2x6/xLYEAsCck=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>orSv77rihmnY1nnntjsNnVZBEisLrbpUAoHN/BlOLSoa0Bmq8nHOrV5JCUld+xxxTYQLQZ2Ej2iFpTG5K7uqeoFBUDI/bLT9u0NUTRcPMI/qQCQLMrhYz6s3A1En+Lm6WqwSJG9ND4TviaPOIn/HRwViPtc5HjAs85EMeenzlGk=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>2pX8PVCgn+Jq9si3uuog6J8kXdDizKTo2skBbmwST9at8LSp4KWtIn0SJBWdcGXjigz924S77Hd/mpDeoe5m5Mq38BLyj2V+zYtEs0eCGauYrJTKwetNqzDn3QLrxyvdVeZtPC8D+ZlwGUm4f8IIXBttgnShDXI7c27kUYyV1I0=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>uq2rVzG1rG6wsZNzy4JhSL3TXYM8mICKxdycPZwOVwyRQjl7NAE50qSr6qGdz7m0Ecv28xXpkFP01NIsDiTIaP/YoyG0fUvMsuxbwK12PW9kkQt40DPDhNPjqm4JX9+YqlwoVibjIgVOhigLlc4vTRPu8jykixBSWcN5XVtaQtk=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>sPpMUP6a1kIuneg4YTZ9AOkZM7t7gKQGKbM5iVC6569YFntUAnD8GwUK9Ey/ZRkrYWqkkX8xT8MohzgdaGNVVjeHgsgvA+hEpkwwGW1TB0W0RsGAgvtqGaJIvMkJeOxlOuW0MhncCGMIGgrVs0EElIpjHJ9xBVavkrYdjpZkrpU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>wvxpiv8DoNP4aWmc/8hqAf/IpbDPRoO5Q3eL192hIJXEQ+EQBjV/jbU41kU6gpYOQ1rUP44JM3onXyoQyOQr3aSLSF7pIm0LuWUCV5lMvl2T84gmknQvbfQj1OZgOYLpyu9J0meNdabQumnecM9O8I8wTVeiF0Kj8XqCXA+2ib0=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>0huty1XmpiS+m+npLBwo/7mrZZmU+iLgL0jHmatfrQBmrSPReCSnpxlmcmwPXQS0udQ4tcKadr68mVUHPaLoaU+ieRK/Qt49n2SIZH9/SdnZrhkbaFPLzVvH6l3HaubJvzNjU6k2wkZHFhh+z0vUeWmNjzXHyMdT0uQK3NmgEpE=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>",
                "<RSAKeyValue><Modulus>0DCtL05ndA1knNvANM2vx0SgjkS93JrqD0X0Kx2MeoFZGgEzPNWAkpB/cvT4mmii00gLkSbtx7UOzDc+F8vT62NLfTYihevL23xTzQJEUUpAe2ZsmjMwThvEln26qVRoqqh4VomyMZ159NZvIy5Lz86Oao0M/sUIPxwz3heVFnU=</Modulus><Exponent>AQAB</Exponent><P>5PZNYIcTUZAzVvaCxOulz4WKQhjIVr8htSyw1ahvsLnXVS5U2WuzTZYhc+ajb7TYqm4T9nSuypeyy4zGzLX7/w==</P><Q>6MZr9yMNMKefE12fM0xwKAMhMR+yXUaFKxIPrdA5XLU45CnLCZNtTEp/fvfPZUTFcdIrsgDhMvJVT4WwUka9iw==</Q><DP>nHLEKnoBtFDRE9H7Ru0x7lv0PffLJKMTOEXiSwv9zYce4SB4b9wMt64nC5gEAzdSeRogX94Y9Wu0iyuVR5nuJw==</DP><DQ>REw7z2Z0b0svTIJSvL13xvWsHnq7XczcM9f3y7rlqcLub1un3CZqklDleb6CFqeH+y58bZz+dnFOAKgsJOfmow==</DQ><InverseQ>kjgyglGUWIeLhbu987WMbLbJJhHyEPOrWukemf3s3V+s7sIrfFl/BRdt6IdPZFRhN2KcE9xQnfwMNqeZqVypTA==</InverseQ><D>xgF1xadhN3xBc/qBDPePtgssVQNGPFnOQSZ8OsGIyT7aeqQnkjVRapQp0zDwfRMFwcMk1THcNvOaFlp4IPPIIcIPnXSqt3E5zYKj0o3k4ksi7ZHWwhoVfCZdfQze1xqp7NrCHZhgeM8edi3agA8D4KZAOAVfjYoJHdFibmgDqWU=</D></RSAKeyValue>"]
        )->map(function($item, $key){
            $RSA = new RSA();
            $RSA->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
            $RSA->setSignatureMode(RSA::SIGNATURE_PKCS1);
            $RSA->setHash('sha256');
            $RSA->setMGFHash('sha256');
            $RSA->loadKey($item);
            return $RSA;
        })->toArray();
        /*$this->PrivateKeys = collect(
            ["<RSAKeyValue><Modulus>0dtbkUTfa10XFaCZz79PtBT2LCpTCuCRcyaHeGmEO5d9D8xy8CvEpWluOROfOZwHSKBwbjJL2EKUvxhRZ/t8G2zBHXaeD9ZvyBaggMH7snPRPTqin9xM1nPmZfcf52hgsZYlnyR5z1FMq+G3q80x9iNxeJhCfm7EktDeUddgOtU=</Modulus><Exponent>AQAB</Exponent><P>3a9PMd6opWcYcXC3J0GpN7rwh3Tx8hxEoLSDtLJt7MEdIOaOpT8KN1FVenhrln2SCOXr8S11hyV20Ncufwiatw==</P><Q>8ldX6NizypWYAeNUSaBhqPVmvBzUXCw7KCWzpYPFqROHkQIhjy1SwNNzpCdBwa4r2FziZJVyw5GVWV4ee1760w==</Q><DP>XqUWHqh9QADAAs6oo235HP4G6w4WrdM7yZDIGkGDhOz9zqoghJRhfDSRVfRLmriJvJrNHO7Xmpd4Hrl/9GRsCQ==</DP><DQ>x0EzgbrzkQUQt7Svb94TjjU/5DmcDbE8bpUZMWS4F318QKlM6IkuemchtP2mHlZTrJEqf+M1OkGwV4uAapW8iw==</DQ><InverseQ>rzU+y3F6tj2OkJM5viQ/U1SAWjYFak+8m7+h9be0bpqKrhH10CrYY23pq+TdQq3c5i/DEk+uUvhTn1WgpMo1xA==</InverseQ><D>hj0tS7WkvQblN15uRWf5UhZ+ii3gxPp10BT7X1kPzxbOVwbPFzbIKY9RhI06sx1Nw3Vtte/SeFnO0JZ7rGpALhoFZKTQ5D1Krfo1dG4WXQ1OZAww6RlhSRdNSZvyGqc6Ex0EpUtFLro9O17UwF6XRfF/7EVxW9dgF5wQTh4Tp5k=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>xq8BnLwxBn30gr03JjYbD8y/ZLWrI/av/rgg3WMZHz+44IO2cCj5Rb/RUIZjpeCrUV/tTRhsYfp9kp7ODyyYTW8Tl6bfWbT+DBui/eZ8ZW+iWqXNNODAMBhQFO2oVYet6NYTiBrjmoLeTrF08kC005C6FNjrSFi6TAMClZ2kPsU=</Modulus><Exponent>AQAB</Exponent><P>+aVRPmY/vskBqgiALRbdt6IVekwUb2BVYYhV3b2QG8VzI4GsLYFlXh5g4oWHRddcXiw/odKD/6YPVmXfdeot6w==</P><Q>y72fFiVNRo86J44BUh2RMfYoJulhGTlp6UlEN06c0wJ79QxqbLervnCRfS4dQH954wbHdw2F0HFWN/TIXGkqDw==</Q><DP>3FxB15u33vddAbLZOXO6qFJpNPec/icBlsMaUE34u5rRpGDdE5npWWflS25kVpDgFrxmOrgxDeOOHEx2MIZSmw==</DP><DQ>djPPBkQh7xvNe4FRlth8kmXjqZ+gq8e5gJT3NLcxGOLKE8NeQzDUPSqF3gBRDEho6KPYOWPpDKOUh+ZGEAcbVQ==</DQ><InverseQ>pvX9jeaCIwJhX1F14IC8jIwLVGEdoeluzkfvv5FeuMNMdzgOoFNlqxc7a8ALN6JbuMP4A8zQTwTINPtzkcQv2w==</InverseQ><D>NuvBglXzJ1xB7rHnQMTMDj4G2gvt1u8mDz9OtmPiWeT/cfB2yeJJpaI0dhHwEbZdJQ1jR2Bs9ViMxa7oPwGQx7mMf3QefgI/z6foc6sJFXhP612NpPL1DVGAmOyvnflF+RcOgCWU/o4tedOPn7DYsmD9cCuZ8BtNDG9KzEzbfDE=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>ztKnEcp8IBMSMr/0+jKm80dUhK5ykkMRP/JUyYckeGFAt4B+RQwJbL2/eX2C7KaGj7BmBVfYvNJHKFIqLbwCakg5biShV4iEMh0iiuO7zD+sYTW9/rjZ2RLB0aYWKMg+wNXo2LpSy7ldrJZngsEDyjFKVFl28MbJ8Oc49B77/NU=</Modulus><Exponent>AQAB</Exponent><P>5Mbdwe+8o4uc5wqgDMcMr35SHMdPNsiPdf2iT7Hy9+IW9E1Ouk/SPO9oZSvV0ZTHdqpGKmTmZqefdsRjN94txw==</P><Q>528DESHd7PRBR2s99walnV1ifqmmxlIA24Kjk6Sn6ejPTr+0cpIR1cwrshregnhruLhEOyVrxyCmOXeFV6Dwgw==</Q><DP>zSsjuUdc73bHsdQvkQX59HDfBvwfqEvZjMF4DOzTr0bNuy6RijkHnKo//2t+iERbVaqC3oY6spllQ0gAixvwBQ==</DP><DQ>mkjNeJxlQFRNOHlr48SQn+njgaFDxy5f1/ataf342t0TZuIyP7bVIPxtnNyAveXdmlVtVyjJQwSnC+R8BkEKVQ==</DQ><InverseQ>Z7DFumyoC5zUduoq0vZ8snanvEHIh5WR7yGt3p0S9Vs8PTLj0g8OuQhjB4gc5k64vNEoHnc4bR6tXlso69Xkdg==</InverseQ><D>bWzCfAU3mvi8HiG1iVKXRgutSe3KLFxTmhOqjF12DELYLL5NEFcH/YJ+9dZUGMUaWJepwpXx1CUlBM5EBqJ8W6+RaYmHlSFbmNSkg2qO3Se9BDU3ILHEyMeG2F0XYApXrYMZ0GjfHZDqHi/UuaKo7MhTTGREAr1NRrr3MtCd7lk=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>web4CXubIblHHuD/1AFpEBu23AyN8RtryNvpFeL5GP5NSCWF90RjqunlPEI4Yb6OAUJcGpRUO95KrsXIoJWl+2gaS57cz1nNwLOd9IVmg4SFkaOmgxqV0l5MVRrOSqyuiApkVepg+/Dgqd/i6Qk4qlTlY4EqfUVJI/J3/vUj9oU=</Modulus><Exponent>AQAB</Exponent><P>zxHOSyEEcEvb0E86z6oTQyc/946nRjnhcYH361Vc2UY9S+wYuOYQHqjrkifLqKKrtiZtECL4/A20D72Qf98xKw==</P><Q>77iiwJ4Z9776/bvLVeNCVqXf+sOo4KEHCAQOzAscoZkoxcyu9NKqFkhguDJ+RDe8JkefojIsVJpERG4LaaO/Dw==</Q><DP>kclKgbCcTGfMOgweLujiKOxwZ93ivVmw9NZ5cxGljj+almKCQWOQw9VQDZXBGFh3JoZFURAeVm9bb52aJ70/Aw==</DP><DQ>BHZncTaAPUBs47RPQLYOUhacaMqjpirZOqj4rYu3aLq1K5l32E7jo66NPmCSQSrYcc37hsVwVdLZWnzB8aF+uQ==</DQ><InverseQ>btLtFmt/N/U9w4Nin5xwdJ4OiHIFXUP+8jodzZkAnVAhfRvf5XWKfG/zxfutUtxbHTk6eVOGW5GP+WLLoA/2LA==</InverseQ><D>DJzgj4VZzvTFWo3aNkktM14KJAAxD3jwJu2fGMxd1bFdB49hSndJYGm8fpH0Ju+FPzG42gXCAO7xvrKg3Uk+taG8zwP4z3S6cAp950GW1/THsKWPS40tIF/AQoMXNp9d0ODjD+qEK1p1NIowi+EFhJ9fa+ciW4lPHo46e8ra25U=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>zvrdR9mZwKqVHmPZyqtBKSCxWyWABAB01aVTq7jgOVn0Xx47Vk4/68Eys/ucT8JqEWB3h1PTAqNnQAZ4jdX54RRnAhOOrvYdRr2VOl+HV05Tc12o7p9I+235nhjN7RquIUS0h+7FxwwtUAwAcPZoFbUWkQtJu612l3jVQAVn6Gk=</Modulus><Exponent>AQAB</Exponent><P>6TMZzhAumWYNkWOtMPyWf61bLj5kloM/TP3T5tsruF7TStoNNSH2Lbf5xZTA8OvHQoETxBYE5NNMEKGG88e9ow==</P><Q>4zd+tIMEp7btX9OAIbx/uNa5a/QrQKmaC6kAE19lUzHX/qdgnJOOBz+ByAeqOWMsBLMvSEiKliJGdYsEraiKgw==</Q><DP>PJRy0q4vfNgfFDLhrgUgD6D4O+YneVY1Hood/y3WiLnRh/NnVPMyoaPWdfZzvOJzTBp+CQVzVgJyEyvzA+dSow==</DP><DQ>biSjpitw4UAal342OaRfoaFtJvio0uKkSwy0fa+btZWO5+IJQj2A9uCBMA8PJFx/pRObUwXfMGITF725Gdl+sw==</DQ><InverseQ>Gv2Gc68/umL0nNJnpkkhJy4ojhk9nxNPn/avKX+o9DE1HU9u2omeFJbzmOP9fKdUGvfd3/Ihzy3mUDSqU1WCVA==</InverseQ><D>Hm68Qmg4Nx/dtQ4xY58ygX2RFjm5TN1UUGVym/ke3s/fIm0rhFEVDPDnJ5eDMCcBuXRZnsgwal7kO4G4EAK6Lp5C+rKah2HhnUN7QKf1W5R9XQ2YRe9eU5SXC6jbOdnvFv3MUbacQXerLoc25bNQrsNBVv54TZNPbQ7Qx1U7f5E=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>sCZGipQlDY74f9lWcJYQ5Vy4SXsDbskaF/Vdl3eLdi39e1VJ4cZewobCZNnCQGmL4D9VHVu1Zk1EqVOgbGzS5hJbXP1o7AmRAsm3F93H/20Cu0oEdDZFLV/R3ZhEpQspNg9Ro4wk+4s/432I67itFVlUnRa7Fw2x6/xLYEAsCck=</Modulus><Exponent>AQAB</Exponent><P>w3BDI/9oPhz7S2nmojjgVix+N9Ky/GepUTDtpx3kMTB7loV3BJA647AreTUCGqTp1ejXvdSyHozmjm7sqRSDtw==</P><Q>5rvbCBBKjzmumWCqN8lwY8GWlYmZz71hI3r0wszi8d/kDDqu1UxC+zA7RRwxMTdFAE5LyYm4uISkQ1akTWrefw==</Q><DP>hEfJyeWnYtMu635EUmx4uxrmW1ZLOzfWjFO3pzM+LmUIYXr4cahFk7K72hAc2nfgpZ4x2BTMco0fyCwgFtkBxQ==</DP><DQ>mPV0MJZMN0KwArHYZf8+aINPhnaf5t3O5ax/UhxtXpdkIM9OU8yoosjIofocilnvflJpX4PTV09nbdSkO0ZblQ==</DQ><InverseQ>bn8EWu6JmW9B09Y44nQHEOhRMYfBpOSIHrMqBoWwVhTGpn7joPk3vBQcCM2Jup8IJ4jvaKtn0shVKP+44IZF9w==</InverseQ><D>CQPMdGckhk8c1J4w2E41yyc1nz0WbeMI3NUH1yqdDo1H/bndsBpQ2y++Xx7t3BIKwI09M+VvoS6vJuNKwIHJQTs7v8FDCbTVX5tHp5k/0rMDiYmzqWC7CE50gShcHQk1zDN+NqqbHyNlN6MpnNykzEdFiOUXXxDkoFRmyLcCiQk=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>orSv77rihmnY1nnntjsNnVZBEisLrbpUAoHN/BlOLSoa0Bmq8nHOrV5JCUld+xxxTYQLQZ2Ej2iFpTG5K7uqeoFBUDI/bLT9u0NUTRcPMI/qQCQLMrhYz6s3A1En+Lm6WqwSJG9ND4TviaPOIn/HRwViPtc5HjAs85EMeenzlGk=</Modulus><Exponent>AQAB</Exponent><P>07okRy5he1JQpqEvwTayUnIYWWBtEzXjThjGvThxpH2HwyYkQmrfdGQiOp+QJINw3rOf0wGL0uWsnK1AWSKeIw==</P><Q>xLppF5wKC1T6miqdaMWNz1PT+XMEGed95wNoz3iDwjHcJVBlX8tTRj6/LwahJQktzjzId555HRLDjetgNZf+Aw==</Q><DP>B5reBriBw943C2Uqw/Pk/9UdTsiJ4nDDE2EMcmt+mXvwzm+mScRw9ktKd3SXxtf5y+YDzZ52mUKqqo4Gi60UpQ==</DP><DQ>sicxxoyrV+nD69rNo7TLoEaEvvCWdTL7BDpFLyUvBKyMdUoiE6cYqZNnJRn20ohGb+8Bh7n+agK1Dw9pl/8lqw==</DQ><InverseQ>FdUTC3owBlBB0nxr8jRjhyyt7nbZoK9XydPr4UYMo0AnJJrpeGEPJO11xrD+AKBeT1sRPgFbipri5ABX7mFLfQ==</InverseQ><D>KwtWSDvO1fSGsycJEALLdUZubJoKuulCdG9WyU0Ai55J/afsu7s0DFWXSOep/1bk+rbmK3O6sB3czXk8Hb/GN7qPATPkDm3t3DAvYHidvBHHdo0xDPkCvhRswH2HZs+/ZM0XC3sag0rZwN+x1XSdTBAp/bKwhiegPOsL1wL1LWk=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>2pX8PVCgn+Jq9si3uuog6J8kXdDizKTo2skBbmwST9at8LSp4KWtIn0SJBWdcGXjigz924S77Hd/mpDeoe5m5Mq38BLyj2V+zYtEs0eCGauYrJTKwetNqzDn3QLrxyvdVeZtPC8D+ZlwGUm4f8IIXBttgnShDXI7c27kUYyV1I0=</Modulus><Exponent>AQAB</Exponent><P>4FAgb/LPPMC/hTWMItw9ybzQbb+8wbPbF/84t9c6yIXn1qDz2JYefHIqUDCwTqt5Zei6fgImu8FTZEHMsZtXlw==</P><Q>+Xa/dA87l86Q5t61hpQpp8D8hsugl1vO0fvDz2EyiQD8JaV+d/UKRS54cBsICae/lq+4MR8gyJ7OiW2htGMZew==</Q><DP>XM5qDUdNnNo2ozubZlTvL4dySOYah54NWOlcoxtmk85rNG7VVNgPdveZMCJk37ese5CFkFr9gZMkGIfs09Lssw==</DP><DQ>m1ljqHbD+aldeyg9iu1Bc4IuwBKWkytNBF7wdXhl7AN9AkSpGTKzjpl9zvJGcxzPppsO7SghVk3u+I4ZISiFeQ==</DQ><InverseQ>0BmFbd9syVYxZlBnQErOrxXiKj4MlBOLqzQNSJv0OJs0qkslR629MW+pX/LhQii1iEZvzjmVEYJqs+WWSQ3O2g==</InverseQ><D>hsDD+cDE5QweSPlAWxiPJRlGwBdQWjyn2IjmOv6g41tDbArUV0uLyqE0F87DelE1zxtnPHc9qu+YWN9KgKWKBAnpcsKdP/xhx878fG6oFim/+3C84/FMDFl/Gnzn+lKhzCQ/uV9PN+BzY24hA2ZlZrtROiHVPV3/QOXYhth5p7k=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>uq2rVzG1rG6wsZNzy4JhSL3TXYM8mICKxdycPZwOVwyRQjl7NAE50qSr6qGdz7m0Ecv28xXpkFP01NIsDiTIaP/YoyG0fUvMsuxbwK12PW9kkQt40DPDhNPjqm4JX9+YqlwoVibjIgVOhigLlc4vTRPu8jykixBSWcN5XVtaQtk=</Modulus><Exponent>AQAB</Exponent><P>7A0wAKkk4SUn02qqxy6V6ZhszJfw32yj2KEgfDlZa5kviAlDIZUzqFeeb/+L/M7rG0K2jtJf5QNmCDP2yd3w4w==</P><Q>ynRUCVIhZ4lMl/TjSVICDLwzLg+FdDyJynQ7tXvDEqyQaerPwX+uyjRazq5fVhHJbQa22IBc++0jucxL8OC2Ew==</Q><DP>ay65wzMKeejjIo5mqTav/3ekv2mmh+zOoQjYtU9dGQxflseN4evbnu7aFETXDZ3vB1vNhSBlRpm6dLMTtim+vQ==</DP><DQ>TMctD5PcIz3IlGBEyguVx2qgJjmwrNJAl0zZUAwtgKl84w4v1/wqN3j/bx5l/WglcXl4Ykbb/JaEU/QMYL93jQ==</DQ><InverseQ>W22XMEzH9lWKDKTk0e5rgukahHOPRzFQSOtligk5T3LwRujc6OlKDhaT5DHcqD2cwdr3MOehk2Jgf/0cOGmW5w==</InverseQ><D>gORcg229JHug4FsQ/pjdFt6KS+zLL/jA2HDEJ/3SIOR6fyE1IekexYQRd4VzcvOjkw4jSh/9DjwJtdAPvwxCmp4W208EASnT/hANEd0x9rip+yZDhiynwz/dkkMxYvyVxWjjAPaWN2rbjXOryoQs9kKlXBv1yckJXhdGSM5P4sk=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>sPpMUP6a1kIuneg4YTZ9AOkZM7t7gKQGKbM5iVC6569YFntUAnD8GwUK9Ey/ZRkrYWqkkX8xT8MohzgdaGNVVjeHgsgvA+hEpkwwGW1TB0W0RsGAgvtqGaJIvMkJeOxlOuW0MhncCGMIGgrVs0EElIpjHJ9xBVavkrYdjpZkrpU=</Modulus><Exponent>AQAB</Exponent><P>2A/RWe1MJz1KIYpipjB7Yz/lOeTQFQ+Ks44uMyXll9JDsvtcZT1S/hrRcdy635AMTvPKoGCGZsXZlujJ+VWxMw==</P><Q>0bD/XXnD/on24lwy/i/jUR4AzwStHEz0bdAWuHJJBhGxnNHkiJsWjnHzliMz2m4c8CmRS3VvP4nTINSmch0xFw==</Q><DP>VWS7C128UW3p1KPLJX/X5HwmbuE+VHSWDy1MmS6LNZwG4cBy3hiEqqWSzfu9aAqYssOr/ALqW8gqnXhYC1GQ/Q==</DP><DQ>oIRVd0DW/+qsyuq5Pkt6P4YwKEj0G03tboJ0Yg1aSQMSa3Fg+BGAWtpwFOYts2/HRzEKwDDNUF44+FDQeVpPUQ==</DQ><InverseQ>Fi8p6UiyNy1WRLXfJ/r2iYqkh7iWeUMjm3wQ0kP/h7+rDXuHm6nwf7nWkuBbOHqGLr/Df7CKO6AIbGtbgey+WQ==</InverseQ><D>K3oR7YPevewcT6aQ0zDznJmnG1JfkMa6zNu+ssEmaWxmE3/Qr5jaFjky67k++7MnORNdwnCgyJuFBVL/xnlAqTj7Kxt8Q6Hv9EXB6aIgGtE5iyFN2Jo+pUouT6wS/NI0Z8ffqDfL2+WM/uFnapRRO2KOjDyTR1No+7N2IXMjW7E=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>wvxpiv8DoNP4aWmc/8hqAf/IpbDPRoO5Q3eL192hIJXEQ+EQBjV/jbU41kU6gpYOQ1rUP44JM3onXyoQyOQr3aSLSF7pIm0LuWUCV5lMvl2T84gmknQvbfQj1OZgOYLpyu9J0meNdabQumnecM9O8I8wTVeiF0Kj8XqCXA+2ib0=</Modulus><Exponent>AQAB</Exponent><P>0OcXKvpDjQUnxVJCjN1TYqTy/ixuuXfHUWZp1V1wSPRGjF4EfHWZIhocWlLwrzz6nvq9dZMlRTHGrdd4qCRLRw==</P><Q>7vIc3GodWmzBkhBubWnF6DHJszwZVqPLktrWE9+jQ9xu243K+agL5eKsZu5M1V2bjoa1fc8ufDLsNDSBsI682w==</Q><DP>tmRN8hQ3znRl2P6NZHgJSeNC1XDt67kMqaGFXekLFGdTAVNFD3WNkhzCDIrF4fqXxx6dNH6Y1+Ux1q5+hi/KGQ==</DP><DQ>Xoha8rjotAWi726gwZi/O4W3DroQLD7RI2CAoYwww4BKO4J1cyAQ1PpAytgsfZ1zFYgl9YgqE+jxmhXL2VYfUQ==</DQ><InverseQ>u7liWwby0jnCs72Dd7kfxxDkVwJrS9ZPYe2OsD1U307D6LQouz1NctkQzsQm5nV6lVSRuTszGxpl0QgPIFWr2A==</InverseQ><D>Tq6osGxDvIyM+4ygzB7FmFTtVN9KZTwWcIE3Lz7CHHITJwkU/ExuUnLEosKA391JmCh46cSZNeqfJKnG6CobALsCmW7PpYASF4DH4ebd0kUDkbOKMIs97/IzDsgvfMwYRzW+3BUtd9P/1s6h0ObebHJqqro1eOEY8lt3uequQYk=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>0huty1XmpiS+m+npLBwo/7mrZZmU+iLgL0jHmatfrQBmrSPReCSnpxlmcmwPXQS0udQ4tcKadr68mVUHPaLoaU+ieRK/Qt49n2SIZH9/SdnZrhkbaFPLzVvH6l3HaubJvzNjU6k2wkZHFhh+z0vUeWmNjzXHyMdT0uQK3NmgEpE=</Modulus><Exponent>AQAB</Exponent><P>+J+vSbVHFKa/w8S73D9ltmN7lhksTRc14ZyxhMM46HVqttjYWWBnxcLU96Uk3A29Un8vyMnGCRxc263wEi6A9w==</P><Q>2Fd3DsVfE6+DawbFF9RxwshtaV6+jl7b9f59h1LP9WjymrCe78if7aX9zQLHs1M1MDFB3told6wIyAODQpautw==</Q><DP>SK21eiCJ9NmB+WKLYCkQPK78M7aMIsUENT2vDxZajmm0llrxc78g1sjLpNEb41AI5hLUDygcwhEOfBFuuoHppQ==</DP><DQ>XIq9kQH4s7EGZEfXE2R67TlTJBm1Ja2KJeYgyDwyXOXxsR4QPr3xO3Uf5gxkZF8Zh23qoWtrT4+1iemGwns2bQ==</DQ><InverseQ>dkUMhGvIGHc05nKS8+FUnMipg9yGm2B2QE5Zj3pkHmkpe3bQIEhQWhd4rC7K6HkMUtJncayC2ZMCuSX7Lq5MiA==</InverseQ><D>ARmB/FlzvpTcpoY/4So4kLYRDpQSMojyMW0pFkXOV2J0Jx3T39QZSIeagKjOH8eBhqN0tIbTNiHjbM4EHq92lewUy3cMGuHc1x+04F//bPbSkgCDWYnCsRuM/GH+EqxHRxjeggfCqqJQJXwP2NnxpNgaeigfSe4vlp/KmAHI/30=</D></RSAKeyValue>",
                "<RSAKeyValue><Modulus>0DCtL05ndA1knNvANM2vx0SgjkS93JrqD0X0Kx2MeoFZGgEzPNWAkpB/cvT4mmii00gLkSbtx7UOzDc+F8vT62NLfTYihevL23xTzQJEUUpAe2ZsmjMwThvEln26qVRoqqh4VomyMZ159NZvIy5Lz86Oao0M/sUIPxwz3heVFnU=</Modulus><Exponent>AQAB</Exponent></RSAKeyValue>"]
        )->map(function($item, $key){
            $RSA = new RSA();
            $RSA->loadKey($item);
            $RSA->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
            $RSA->setSignatureMode(RSA::SIGNATURE_PKCS1);
            $RSA->setHash('sha256');
            $RSA->setMGFHash('sha256');
            return $RSA;
        })->toArray();*/


        /*$this->generatedDate = Carbon::now();
        $this->Options = (object) [
            'runTime' => false,
            'executions' => false,
            'lockTo' => false,
            'expiredDate' => false,
            'maxDays' => false,
            'assemblyName' => false,
            'assemblyToken' => false,
            'assemblyMinVersion' => false,
            'assemblyMaxVersion' => false
        ];
        $this->optionsData = (object) [
            'runTime' => null,
            'executions' => null,
            'lockTo' => null,
            'expiredDate' => null,
            'maxDays' => null,
            'assemblyName' => null,
            'assemblyToken' => null,
            'assemblyMinVersion' => null,
            'assemblyMaxVersion' => null
        ];
        $this->Features = new Collection();
        $this->userData = new Collection();*/
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'UID' => $this->xLicense->uid,
            'appName' => $this->xLicense->appName,
            'Serial' => $this->xLicense->serial,
            'DeactivateCode' => $this->xLicense->deactivateCode,
            'generatedDate' => $this->xLicense->generatedDate,
            'Options' => $this->xLicense->options,
            'optionsData' => $this->xLicense->optionsData,
            'userData' => $this->xLicense->userData,
            'Features' => $this->xLicense->features,
            "SupportId" => $this->xLicense->supportId
        ];
    }

    function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        return collect($item)
            ->reject(function ($item, $key) {
                if(is_array($item))
                    return count($this->array_remove_null($item)) == 0;
                if($item instanceof \ArrayIterator)
                    return count($this->array_remove_null($item)) == 0;
                else
                    return is_null($item);
            })->flatMap(function ($item, $key) {
                return is_numeric($key)
                    ? [$this->array_remove_null($item)]
                    : [$key => $this->array_remove_null($item)];
            })
            ->toArray();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function EncryptArray()
    {
        return $this->array_remove_null([
            'a00' => $this->xLicense->uid,
            'a01' => $this->xLicense->appName,
            'a02' => $this->xLicense->serial,
            'a03' => $this->xLicense->deactivateCode,
            'a04' => $this->xLicense->generatedDate->toIso8601String(),
            'a05' => $this->xLicense->maxDays,
            'a06' => [
                'c00' => $this->xLicense->options['runTime'] ? 1 : 0,
                'c01' => $this->xLicense->options['executions'] ? 1 : 0,
                'c02' => $this->xLicense->options['lockTo'] ? 1 : 0,
                'c03' => $this->xLicense->options['expiredDate'] ? 1 : 0,
                'c04' => $this->xLicense->options['maxDays'] ? 1 : 0,
                'c05' => $this->xLicense->options['assemblyName'] ? 1 : 0,
                'c06' => $this->xLicense->options['assemblyToken'] ? 1 : 0,
                'c07' => $this->xLicense->options['assemblyMinVersion'] ? 1 : 0,
                'c08' => $this->xLicense->options['assemblyMaxVersion'] ? 1 : 0
            ],
            'a07' => [
                'b00' => $this->xLicense->optionsData['runTime'],
                'b01' => $this->xLicense->optionsData['executions'],
                'b02' => $this->xLicense->optionsData['lockTo'],
                'b03' => is_null($this->xLicense->optionsData['expiredDate']) ? null :  $this->xLicense->optionsData['expiredDate']->toIso8601String(),
                'b04' => $this->xLicense->optionsData['maxDays'],
                'b05' => $this->xLicense->optionsData['assemblyName'],
                'b06' => $this->xLicense->optionsData['assemblyToken'],
                'b07' => $this->xLicense->optionsData['assemblyMinVersion'],
                'b08' => $this->xLicense->optionsData['assemblyMaxVersion']
            ],
            'a08' => is_null($this->xLicense->userData) ? null : $this->xLicense->userData->getIterator(),
            'a09' => $this->xLicense->features->map(function ($item) {
                return $item ? 1 : 0;
            })->getIterator(),
        ]);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return string
     */
    public function Encrypt()
    {
        //dd(\GuzzleHttp\json_encode($this->EncryptArray(),128));

        $isoJson = \GuzzleHttp\json_encode($this->EncryptArray());
        $Signature = base64_encode($this->PublicKeys[12]->sign($isoJson));

        $Shuffled = $this->GetShuffledText($isoJson)->shuffle();
        $Map = base64_encode($this->PublicKeys[11]->encrypt(($Shuffled->pluck('map'))));

        $EncryptedData = $this->EncryptLicenseData($Shuffled->pluck('text'));
        $Data = collect([$Signature, $Map])->merge($EncryptedData);

        return $Data->implode("\r\n");
    }

    /**
     * Convert the model instance to an array.
     *
     * @param $Data Collection
     * @return Collection
     */
    public function EncryptLicenseData(Collection $Data)
    {
        $EncryptedData = new Collection();
        collect(str_split($Data->implode(''), 117))->each(function ($item, $key) use(&$EncryptedData){
            $EncryptedData->push(base64_encode($this->PublicKeys[$this->keyId($key, 10)]->encrypt($item)));
        });
        return $EncryptedData;
    }


    private function keyId($id, $max){
        while ($id >= $max)
            if ($id >= $max)
                $id -= $max;
        return $id;
    }

    /**
     * Convert the model instance to an array.
     *
     * @param  string  $text
     * @return Collection
     */
    public function GetShuffledText(string $text)
    {
        $maxLen = strlen($text);
        $xLen = floor((double) $maxLen / ($maxLen < 50 ? 3 : 5));
        $exchanges = new Collection();
        for ($i = 0;$i < $maxLen; $i += $xLen)
        {
            if ($i + $xLen < $maxLen)
                $exchanges->push([
                    'text' => substr($text, $i, $xLen),
                    'map' => [(int)$i,(int)($i + $xLen - 1)]
                ]);
            else
                $exchanges->push([
                    'text' => substr($text, $i, $xLen),
                    'map' => [(int)$i,(int)($maxLen - 1)]
                ]);
        }
        return $exchanges;
    }
}