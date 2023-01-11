<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ' :attribute jābūt pieņēmtam.',
    'active_url' => ' :attribute nav derīgs URL.',
    'after' => ' :attribute jābūt datumam pēc :date.',
    'after_or_equal' => ' :attribute jābūt datumam pēc vai vienādam ar :date.',
    'alpha' => ' :attribute sastāvā drīkst būt tikai burti.',
    'alpha_dash' => ' :attribute sastāvā drīkst būt tikai burti, cipari, domuzīmes un apakšsvītras.',
    'alpha_num' => ' :attribute sastāvā drīkst būt tikai burti un cipari.',
    'array' => ' :attribute jābūt masīvam.',
    'before' => ' :attribute jābūt datumam pirms :date.',
    'before_or_equal' => ' :attribute jābūt datumam pirms vai vienādam ar :date.',
    'between' => [
        'numeric' => ' :attribute jābūt starp :min un :max.',
        'files' => ' :attribute jābūt starp :min and :max kilobaiti.',
        'string' => ' :attribute jābūt starp :min and :max rakstzīmes.',
        'array' => ' :attribute jābūt starp :min and :max vienumi.',
    ],
    'boolean' => ' :attribute laukam jābūt patiesam vai aplamam. ',
    'confirmed' => ' :attribute apstiprinājums nesakrīt.',
    'date' => ' :attribute nav derīgs datums.',
    'date_equals' => ' :attribute jābūt datumam vienādam ar :date.',
    'date_format' => ' :attribute nesakrīt ar formātu :format.',
    'different' => ' :attribute un :cits jābūt dažādiem.',
    'digits' => ' :attribute jābūt :cipari cipari.',
    'digits_between' => ' :attribute jābūt starp :min and :max cipariem.',
    'dimensions' => ' :attribute ir nederīgas attēla dimensijas.',
    'distinct' => ' :attribute laukam ir vērtības dublikāts.',
    'email' => ' :attribute jābūt derīgai e-pasta adresei.',
    'ends_with' => ' :attribute jābeidzas ar vienu no sekojošām: :values.',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'file' => ' :attribute ir jābūt failam.',
    'filled' => ' :attribute laukam ir jābūt value.',
    'gt' => [
        'numeric' => ' :attribute jābūt lielākam par :value.',
        'file' => ' :attribute jābūt lielākam par :value kilobaiti.',
        'string' => ' :attribute jābūt lielākam par :value rakstzīmes.',
        'array' => ' :attribute jābūt lielākam par :value vienības.',
    ],
    'gte' => [
        'numeric' => ' :attribute jābūt lielākam vai vienādam ar :value.',
        'file' => ' :attribute jābūt lielākam vai vienādam ar :value kilobaiti. ',
        'string' => ' :attribute jābūt lielākam vai vienādam ar :value rakstzīmes.',
        'array' => ' :attribute jāiekļauj :value vienības vai vairāk.',
    ],
    'image' => ' :attribute jābūt bildei.',
    'in' => 'Izvēlētais :atribūts ir nederīgs.',
    'in_array' => ' :attribute lauks nepastāv iekš :other.',
    'integer' => ' :attribute ir jābūt veselam skaitlim.',
    'ip' => ' :attribute ir jābūt derīgai IP adresei.',
    'ipv4' => ' :attribute ir jābūt derīgai IPv4 adresei.',
    'ipv6' => ' :attribute ir jābūt derīgai IPv6 adresei.',
    'json' => ' :attribute jābūt derīgai JSON virknei.',
    'lt' => [
        'numeric' => ' :attribute jābūt mazākam par :value.',
        'file' => ' :attribute jābūt mazākam par : value kilobaiti.',
        'string' => ' :attribute jābūt mazākam par :value rakstzīmes.',
        'array' => ' :attribute jābūt mazākam par :value vienības.',
    ],
    'lte' => [
        'numeric' => ' :attribute jābūt mazākam vai vienādam ar :value.',
        'file' => ' :attribute jābūt mazākam vai vienādam ar :value kilobaiti.',
        'string' => ' :attribute jābūt mazākam vai vienādam ar :value rakstzīmes.',
        'array' => ' :attribute jābūt mazākam vai vienādam ar :value vienības.',
    ],
    'max' => [
        'numeric' => ' :attribute nedrīkst būt lielāks par :max.',
        'file' => ' :attribute nedrīkst būt lielāks par :max kilobaiti.',
        'string' => ' :attribute nedrīkst būt lielāks par :max rakstzīmes.',
        'array' => ' :attribute nedrīkst būt lielāks par :max vienības.',
    ],
    'mimes' => ' :attribute jābūt failam ar tipu : :values.',
    'mimetypes' => ' :attribute jābūt failam ar tipu : :values.',
    'min' => [
        'numeric' => ' :attribute jābūt vismaz :min.',
        'file' => ' :attribute jābūt vismaz :min kilobaiti.',
        'string' => ' :attribute jābūt vismaz :min rakstzīmes.',
        'array' => ' :attribute jābūt vismaz :min vienības.',
    ],
    'multiple_of' => ' :attribute must be a multiple of :value.',
    'not_in' => 'Izvēlētais :attribute ir nederīgs.',
    'not_regex' => ' :attribute formāts ir nederīgs.',
    'numeric' => ' :attribute jābūt ciparam.',
    'password' => 'Parole ir nepareiza..',
    'present' => ' :attribute field must be present.',
    'regex' => ' :attribute formāts ir nederīgs.',
    'required' => ' :attribute laukam jābūt aizpildītam.',
    'required_if' => ' :attribute laukam jābūt aizpildītam ,kad : other ir :value.',
    'required_unless' => ' :attribute laukam nav jābūt aizpildītam, ja :other ir iekš :values.',
    'required_with' => ' :attribute laukam jābūt aizpildītam, kad :values is present.',
    'required_with_all' => ' :attribute laukam jābūt aizpildītam, kad :values are present.',
    'required_without' => ' :attribute laukam jābūt aizpildītam ,kad :values is not present.',
    'required_without_all' => ' :attribute laukam jābūt aizpildītam, kad neviens no :values are present.',
    'prohibited' => ' :attribute lauks ir aizliegts.',
    'prohibited_if' => ' :attribute lauks ir aizliegts, kad :other ir :value.',
    'prohibited_unless' => ' :attribute lauks ir aizliegts, ja vien :other ir iekš :values.',
    'same' => ' :attribute un :other ar citu jāatbilst.',
    'size' => [
        'numeric' => ' :attribute jābūt :size.',
        'file' => ' :attribute jābūt :size kilobaitos.',
        'string' => ' :attribute jābūt :size rakstzīmēs.',
        'array' => ' :attribute sastāvā jābūt :size vienībās.',
    ],
    'starts_with' => ' :attribute jāsākas ar vienu no sekojošiem: :values.',
    'string' => ' :attribute jābūt virknei.',
    'timezone' => ' :attribute jābūt derīgai zonai.',
    'unique' => ' :attribute jau ir aizņemts.',
    'uploaded' => ' :attribute neizdevās augšupielādēt.',
    'url' => ' :attribute formāts ir nederīgs.',
    'uuid' => ' :attribute jābūt derīgam UUID.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Vārds',
        'surname' => 'Uzvārds',
        'email' => 'E-pasts',
        'password' => 'Parole',
        'roles' => 'Lomas',
        'user' => 'Lietotājs',
        'file' => 'Fails',
        'user_id' => 'Lietotājs',
        'comment' => 'Komentārs',
        'password_rep' => 'Atkārtoti parole',
        'birthday' => 'Dzimšanas diena',
        'language' => 'Valoda',
        'gender' => 'Dzimums',
    ],

];
