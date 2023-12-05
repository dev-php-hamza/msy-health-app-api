<?php

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesWithCodes = $this->countriesWithCodes();
        foreach ($countriesWithCodes as $key => $country) {
            $country = Country::updateOrCreate([
              'territory_code' => $country['code'],
            ],[
              'name' => $country['name'],
              'calling_code' => $country['calling_code'],
            ]);
        }
        // $countries = Country::all()->count();
        // if ($countries) {
        //  # Add Codes To These Countries
           //  foreach ($countriesWithCodes as $code => $country) {
           //      # if this country is in countries table # put this code in code column
        //         Country::where('name', $country)->update(['code' => $code]);
           //  }
        // } else {
           //  # Add Countries Along With Its Codes
           //  foreach ($countriesWithCodes as $code => $country) {
           //      # if this country is in countries table # put this code in code column
        //         Country::create([
        //          'name' => $country,
        //          'code' => $code,
        //         ]);
           //  }
        // }
    }
    /**
     * Get Countries With Codes
     *
     * @return array countries with codes 
     */
    public function countriesWithCodes()
    {
        // REF: https://gist.github.com/DHS/1340150

        $countriesWithCodes = array();
        $countriesWithCodes[] = array('name'=>'Afghanistan', 'code'=>'AF', 'calling_code'=>93);
        $countriesWithCodes[] = array('name'=>'Albania', 'code'=>'AL', 'calling_code'=>355);
        $countriesWithCodes[] = array('name'=>'Algeria', 'code'=>'DZ', 'calling_code'=>213);
        $countriesWithCodes[] = array('name'=>'American Samoa', 'code'=>'AS', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Andorra', 'code'=>'AD', 'calling_code'=>376);
        $countriesWithCodes[] = array('name'=>'Angola', 'code'=>'AO', 'calling_code'=>244);
        $countriesWithCodes[] = array('name'=>'Anguilla', 'code'=>'AI', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Antarctica', 'code'=>'AQ', 'calling_code'=>762);
        $countriesWithCodes[] = array('name'=>'Antigua and Barbuda', 'code'=>'AG', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Argentina', 'code'=>'AR', 'calling_code'=>54);
        $countriesWithCodes[] = array('name'=>'Armenia', 'code'=>'AM', 'calling_code'=>374);
        $countriesWithCodes[] = array('name'=>'Aruba', 'code'=>'AW', 'calling_code'=>297);
        $countriesWithCodes[] = array('name'=>'Australia', 'code'=>'AU', 'calling_code'=>61);
        $countriesWithCodes[] = array('name'=>'Austria', 'code'=>'AT', 'calling_code'=>43);
        $countriesWithCodes[] = array('name'=>'Azerbaijan', 'code'=>'AZ', 'calling_code'=>994);
        $countriesWithCodes[] = array('name'=>'Bahamas', 'code'=>'BS', 'calling_code'=>1242);
        $countriesWithCodes[] = array('name'=>'Bahrain', 'code'=>'BH', 'calling_code'=>973);
        $countriesWithCodes[] = array('name'=>'Bangladesh', 'code'=>'BD', 'calling_code'=>880);
        $countriesWithCodes[] = array('name'=>'Barbados', 'code'=>'BB', 'calling_code'=>1246);
        $countriesWithCodes[] = array('name'=>'Belarus', 'code'=>'BY', 'calling_code'=>375);
        $countriesWithCodes[] = array('name'=>'Belgium', 'code'=>'BE', 'calling_code'=>32);
        $countriesWithCodes[] = array('name'=>'Belize', 'code'=>'BZ', 'calling_code'=>501);
        $countriesWithCodes[] = array('name'=>'Benin', 'code'=>'BJ', 'calling_code'=>229);
        $countriesWithCodes[] = array('name'=>'Bermuda', 'code'=>'BM', 'calling_code'=>1441);
        $countriesWithCodes[] = array('name'=>'Bhutan', 'code'=>'BT', 'calling_code'=>975);
        $countriesWithCodes[] = array('name'=>'Bolivia', 'code'=>'BO', 'calling_code'=>591);
        $countriesWithCodes[] = array('name'=>'Bosnia and Herzegovina', 'code'=>'BA', 'calling_code'=>387);
        $countriesWithCodes[] = array('name'=>'Botswana', 'code'=>'BW', 'calling_code'=>267);
        $countriesWithCodes[] = array('name'=>'Bouvet Island', 'code'=>'BV', 'calling_code'=>47);
        $countriesWithCodes[] = array('name'=>'Brazil', 'code'=>'BR', 'calling_code'=>55);
        $countriesWithCodes[] = array('name'=>'British Indian Ocean Territory', 'code'=>'IO', 'calling_code'=>246);
        $countriesWithCodes[] = array('name'=>'British Virgin Islands', 'code'=>'VG', 'calling_code'=>1284);
        $countriesWithCodes[] = array('name'=>'Brunei', 'code'=>'BN', 'calling_code'=>673);
        $countriesWithCodes[] = array('name'=>'Bulgaria', 'code'=>'BG', 'calling_code'=>359);
        $countriesWithCodes[] = array('name'=>'Burkina Faso', 'code'=>'BF', 'calling_code'=>226);
        $countriesWithCodes[] = array('name'=>'Burundi', 'code'=>'BI', 'calling_code'=>257);
        $countriesWithCodes[] = array('name'=>'Cambodia', 'code'=>'KH', 'calling_code'=>855);
        $countriesWithCodes[] = array('name'=>'Cameroon', 'code'=>'CM', 'calling_code'=>237);
        $countriesWithCodes[] = array('name'=>'Canada', 'code'=>'CA', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Cape Verde', 'code'=>'CV', 'calling_code'=>238);
        $countriesWithCodes[] = array('name'=>'Cayman Islands', 'code'=>'KY', 'calling_code'=>1345);
        $countriesWithCodes[] = array('name'=>'Central African Republic', 'code'=>'CF', 'calling_code'=>236);
        $countriesWithCodes[] = array('name'=>'Chad', 'code'=>'TD', 'calling_code'=>235);
        $countriesWithCodes[] = array('name'=>'Chile', 'code'=>'CL', 'calling_code'=>56);
        $countriesWithCodes[] = array('name'=>'Christmas Island', 'code'=>'CX', 'calling_code'=>61);
        $countriesWithCodes[] = array('name'=>'Cocos [Keeling] Islands', 'code'=>'CC', 'calling_code'=>61);
        $countriesWithCodes[] = array('name'=>'Colombia', 'code'=>'CO', 'calling_code'=>57);
        $countriesWithCodes[] = array('name'=>'Comoros', 'code'=>'KM', 'calling_code'=>269);
        $countriesWithCodes[] = array('name'=>'Congo - Brazzaville', 'code'=>'CG', 'calling_code'=>242);
        $countriesWithCodes[] = array('name'=>'Congo - Kinshasa', 'code'=>'CD', 'calling_code'=>243);
        $countriesWithCodes[] = array('name'=>'Cook Islands', 'code'=>'CK', 'calling_code'=>682);
        $countriesWithCodes[] = array('name'=>'Costa Rica', 'code'=>'CR', 'calling_code'=>506);
        $countriesWithCodes[] = array('name'=>'Croatia', 'code'=>'HR', 'calling_code'=>385);
        $countriesWithCodes[] = array('name'=>'Cuba', 'code'=>'CU', 'calling_code'=>53);
        $countriesWithCodes[] = array('name'=>'Cyprus', 'code'=>'CY', 'calling_code'=>357); 
        $countriesWithCodes[] = array('name'=>'Czech Republic', 'code'=>'CZ', 'calling_code'=>420); 
        $countriesWithCodes[] = array('name'=>'CAte daIvoire', 'code'=>'CI', 'calling_code'=>225);   
        $countriesWithCodes[] = array('name'=>'Denmark', 'code'=>'DK', 'calling_code'=>45); 
        $countriesWithCodes[] = array('name'=>'Djibouti', 'code'=>'DJ', 'calling_code'=>253);   
        $countriesWithCodes[] = array('name'=>'Dominica', 'code'=>'DM', 'calling_code'=>767);   
        $countriesWithCodes[] = array('name'=>'Dominican Republic', 'code'=>'DO', 'calling_code'=>809); 
        $countriesWithCodes[] = array('name'=>'East Germany', 'code'=>'DD', 'calling_code'=>37);    
        $countriesWithCodes[] = array('name'=>'Ecuador', 'code'=>'EC', 'calling_code'=>593);    
        $countriesWithCodes[] = array('name'=>'Egypt', 'code'=>'EG', 'calling_code'=>20);   
        $countriesWithCodes[] = array('name'=>'El Salvador', 'code'=>'SV', 'calling_code'=>503);    
        $countriesWithCodes[] = array('name'=>'Equatorial Guinea', 'code'=>'GQ', 'calling_code'=>240);  
        $countriesWithCodes[] = array('name'=>'Eritrea', 'code'=>'ER', 'calling_code'=>291);    
        $countriesWithCodes[] = array('name'=>'Estonia', 'code'=>'EE', 'calling_code'=>372);    
        $countriesWithCodes[] = array('name'=>'Ethiopia', 'code'=>'ET', 'calling_code'=>251);   
        $countriesWithCodes[] = array('name'=>'Falkland Islands', 'code'=>'FK', 'calling_code'=>500);   
        $countriesWithCodes[] = array('name'=>'Faroe Islands', 'code'=>'FO', 'calling_code'=>298);  
        $countriesWithCodes[] = array('name'=>'Fiji', 'code'=>'FJ', 'calling_code'=>697);   
        $countriesWithCodes[] = array('name'=>'Finland', 'code'=>'FI', 'calling_code'=>358);    
        $countriesWithCodes[] = array('name'=>'France', 'code'=>'FR', 'calling_code'=>33);  
        $countriesWithCodes[] = array('name'=>'French Guiana', 'code'=>'GF', 'calling_code'=>594);  
        $countriesWithCodes[] = array('name'=>'French Polynesia', 'code'=>'PF', 'calling_code'=>689);   
        $countriesWithCodes[] = array('name'=>'French Southern Territories', 'code'=>'TF', 'calling_code'=>33); 
        $countriesWithCodes[] = array('name'=>'French Southern and Antarctic Territories', 'code'=>'FQ', 'calling_code'=>33);   
        $countriesWithCodes[] = array('name'=>'Gabon', 'code'=>'GA', 'calling_code'=>241);  
        $countriesWithCodes[] = array('name'=>'Gambia', 'code'=>'GM', 'calling_code'=>200);
        $countriesWithCodes[] = array('name'=>'Georgia', 'code'=>'GE', 'calling_code'=>995);    
        $countriesWithCodes[] = array('name'=>'Germany', 'code'=>'DE', 'calling_code'=>49); 
        $countriesWithCodes[] = array('name'=>'Ghana', 'code'=>'GH', 'calling_code'=>233);  
        $countriesWithCodes[] = array('name'=>'Gibraltar', 'code'=>'GI', 'calling_code'=>350);  
        $countriesWithCodes[] = array('name'=>'Greece', 'code'=>'GR', 'calling_code'=>30);  
        $countriesWithCodes[] = array('name'=>'Greenland', 'code'=>'GL', 'calling_code'=>299);  
        $countriesWithCodes[] = array('name'=>'Grenada', 'code'=>'GD', 'calling_code'=>1473);   
        $countriesWithCodes[] = array('name'=>'Guadeloupe', 'code'=>'GP', 'calling_code'=>590); 
        $countriesWithCodes[] = array('name'=>'Guam', 'code'=>'GU', 'calling_code'=>1); 
        $countriesWithCodes[] = array('name'=>'Guatemala', 'code'=>'GT', 'calling_code'=>502);  
        $countriesWithCodes[] = array('name'=>'Guernsey', 'code'=>'GG', 'calling_code'=>44);    
        $countriesWithCodes[] = array('name'=>'Guinea', 'code'=>'GN', 'calling_code'=>224); 
        $countriesWithCodes[] = array('name'=>'Guinea-Bissau', 'code'=>'GW', 'calling_code'=>245);  
        $countriesWithCodes[] = array('name'=>'Guyana', 'code'=>'GY', 'calling_code'=>592); 
        $countriesWithCodes[] = array('name'=>'Haiti', 'code'=>'HT', 'calling_code'=>509);  
        $countriesWithCodes[] = array('name'=>'Heard Island and McDonald Islands', 'code'=>'HM', 'calling_code'=>672);  
        $countriesWithCodes[] = array('name'=>'Honduras', 'code'=>'HN', 'calling_code'=>504);   
        $countriesWithCodes[] = array('name'=>'Hong Kong SAR China', 'code'=>'HK', 'calling_code'=>852);    
        $countriesWithCodes[] = array('name'=>'Hungary', 'code'=>'HU', 'calling_code'=>36); 
        $countriesWithCodes[] = array('name'=>'Iceland', 'code'=>'IS', 'calling_code'=>354);    
        $countriesWithCodes[] = array('name'=>'India', 'code'=>'IN', 'calling_code'=>91);   
        $countriesWithCodes[] = array('name'=>'Indonesia', 'code'=>'ID', 'calling_code'=>62);   
        $countriesWithCodes[] = array('name'=>'Iran', 'code'=>'IR', 'calling_code'=>98);    
        $countriesWithCodes[] = array('name'=>'Iraq', 'code'=>'IQ', 'calling_code'=>694);   
        $countriesWithCodes[] = array('name'=>'Ireland', 'code'=>'IE', 'calling_code'=>353);    
        $countriesWithCodes[] = array('name'=>'Isle of Man', 'code'=>'IM', 'calling_code'=>44); 
        $countriesWithCodes[] = array('name'=>'Israel', 'code'=>'IL', 'calling_code'=>972);
        $countriesWithCodes[] = array('name'=>'Italy', 'code'=>'IT', 'calling_code'=>39);   
        $countriesWithCodes[] = array('name'=>'Jamaica', 'code'=>'JM', 'calling_code'=>876);    
        $countriesWithCodes[] = array('name'=>'Japan', 'code'=>'JP', 'calling_code'=>81);   
        $countriesWithCodes[] = array('name'=>'Jersey', 'code'=>'JE', 'calling_code'=>44);  
        $countriesWithCodes[] = array('name'=>'Jordan', 'code'=>'JO', 'calling_code'=>962); 
        $countriesWithCodes[] = array('name'=>'Kazakhstan', 'code'=>'KZ', 'calling_code'=>7);   
        $countriesWithCodes[] = array('name'=>'Kenya', 'code'=>'KE', 'calling_code'=>254);  
        $countriesWithCodes[] = array('name'=>'Kiribati', 'code'=>'KI', 'calling_code'=>686);   
        $countriesWithCodes[] = array('name'=>'Kuwait', 'code'=>'KW', 'calling_code'=>965); 
        $countriesWithCodes[] = array('name'=>'Kyrgyzstan', 'code'=>'KG', 'calling_code'=>996); 
        $countriesWithCodes[] = array('name'=>'Laos', 'code'=>'LA', 'calling_code'=>856);   
        $countriesWithCodes[] = array('name'=>'Latvia', 'code'=>'LV', 'calling_code'=>371); 
        $countriesWithCodes[] = array('name'=>'Lebanon', 'code'=>'LB', 'calling_code'=>961);    
        $countriesWithCodes[] = array('name'=>'Lesotho', 'code'=>'LS', 'calling_code'=>266);    
        $countriesWithCodes[] = array('name'=>'Liberia', 'code'=>'LR', 'calling_code'=>231);    
        $countriesWithCodes[] = array('name'=>'Libya', 'code'=>'LY', 'calling_code'=>218);  
        $countriesWithCodes[] = array('name'=>'Liechtenstein', 'code'=>'LI', 'calling_code'=>423);  
        $countriesWithCodes[] = array('name'=>'Lithuania', 'code'=>'LT', 'calling_code'=>370);  
        $countriesWithCodes[] = array('name'=>'Luxembourg', 'code'=>'LU', 'calling_code'=>352); 
        $countriesWithCodes[] = array('name'=>'Macau SAR China', 'code'=>'MO', 'calling_code'=>853);    
        $countriesWithCodes[] = array('name'=>'Macedonia', 'code'=>'MK', 'calling_code'=>389);
        $countriesWithCodes[] = array('name'=>'Madagascar', 'code'=>'MG', 'calling_code'=>261); 
        $countriesWithCodes[] = array('name'=>'Malawi', 'code'=>'MW', 'calling_code'=>265); 
        $countriesWithCodes[] = array('name'=>'Malaysia', 'code'=>'MY', 'calling_code'=>60);    
        $countriesWithCodes[] = array('name'=>'Maldives', 'code'=>'MV', 'calling_code'=>960);   
        $countriesWithCodes[] = array('name'=>'Mali', 'code'=>'ML', 'calling_code'=>223);   
        $countriesWithCodes[] = array('name'=>'Malta', 'code'=>'MT', 'calling_code'=>356);  
        $countriesWithCodes[] = array('name'=>'Marshall Islands', 'code'=>'MH', 'calling_code'=>692);   
        $countriesWithCodes[] = array('name'=>'Martinique', 'code'=>'MQ', 'calling_code'=>596); 
        $countriesWithCodes[] = array('name'=>'Mauritania', 'code'=>'MR', 'calling_code'=>222); 
        $countriesWithCodes[] = array('name'=>'Mauritius', 'code'=>'MU', 'calling_code'=>230);  
        $countriesWithCodes[] = array('name'=>'Mayotte', 'code'=>'YT', 'calling_code'=>262);    
        $countriesWithCodes[] = array('name'=>'Metropolitan France', 'code'=>'FX', 'calling_code'=>33); 
        $countriesWithCodes[] = array('name'=>'Mexico', 'code'=>'MX', 'calling_code'=>52);  
        $countriesWithCodes[] = array('name'=>'Micronesia', 'code'=>'FM', 'calling_code'=>691); 
        $countriesWithCodes[] = array('name'=>'Midway Islands', 'code'=>'MI', 'calling_code'=>1808);    
        $countriesWithCodes[] = array('name'=>'Moldova', 'code'=>'MD', 'calling_code'=>373);    
        $countriesWithCodes[] = array('name'=>'Monaco', 'code'=>'MD', 'calling_code'=>377); 
        $countriesWithCodes[] = array('name'=>'Mongolia', 'code'=>'MN', 'calling_code'=>976);   
        $countriesWithCodes[] = array('name'=>'Montenegro', 'code'=>'ME', 'calling_code'=>382); 
        $countriesWithCodes[] = array('name'=>'Montserrat', 'code'=>'MS', 'calling_code'=>1);   
        $countriesWithCodes[] = array('name'=>'Morocco', 'code'=>'MA', 'calling_code'=>212);    
        $countriesWithCodes[] = array('name'=>'Mozambique', 'code'=>'MZ', 'calling_code'=>258); 
        $countriesWithCodes[] = array('name'=>'Myanmar', 'code'=>'MM', 'calling_code'=>95); 
        $countriesWithCodes[] = array('name'=>'Namibia', 'code'=>'NA', 'calling_code'=>264);    
        $countriesWithCodes[] = array('name'=>'Nauru', 'code'=>'NR', 'calling_code'=>674);  
        $countriesWithCodes[] = array('name'=>'Nepal', 'code'=>'NP', 'calling_code'=>977);  
        $countriesWithCodes[] = array('name'=>'Netherlands', 'code'=>'NL', 'calling_code'=>31); 
        $countriesWithCodes[] = array('name'=>'Netherlands Antilles', 'code'=>'AN', 'calling_code'=>599);
        $countriesWithCodes[] = array('name'=>'New Caledonia', 'code'=>'NC', 'calling_code'=>687);  
        $countriesWithCodes[] = array('name'=>'New Zealand', 'code'=>'NZ', 'calling_code'=>64); 
        $countriesWithCodes[] = array('name'=>'Nicaragua', 'code'=>'NI', 'calling_code'=>505);  
        $countriesWithCodes[] = array('name'=>'Niger', 'code'=>'NE', 'calling_code'=>227);  
        $countriesWithCodes[] = array('name'=>'Nigeria', 'code'=>'NG', 'calling_code'=>234);    
        $countriesWithCodes[] = array('name'=>'Niue', 'code'=>'NU', 'calling_code'=>683);   
        $countriesWithCodes[] = array('name'=>'Norfolk Island', 'code'=>'NF', 'calling_code'=>672); 
        $countriesWithCodes[] = array('name'=>'North Korea', 'code'=>'KP', 'calling_code'=>850);    
        $countriesWithCodes[] = array('name'=>'North Vietnam', 'code'=>'VD', 'calling_code'=>84);   
        $countriesWithCodes[] = array('name'=>'Northern Mariana Islands', 'code'=>'MP', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Norway', 'code'=>'NO', 'calling_code'=>47);  
        $countriesWithCodes[] = array('name'=>'Oman', 'code'=>'OM', 'calling_code'=>698);   
        $countriesWithCodes[] = array('name'=>'Pakistan', 'code'=>'PK', 'calling_code'=>92);    
        $countriesWithCodes[] = array('name'=>'Palau', 'code'=>'PW', 'calling_code'=>680);  
        $countriesWithCodes[] = array('name'=>'Panama', 'code'=>'PA', 'calling_code'=>507); 
        $countriesWithCodes[] = array('name'=>'Paraguay', 'code'=>'PY', 'calling_code'=>595);       
        $countriesWithCodes[] = array('name'=>'Papua New Guinea', 'code'=>'PG', 'calling_code'=>675);
        $countriesWithCodes[] = array('name'=>"People's Democratic Republic of Yemen", 'code'=>'YD', 'calling_code'=>967);
        $countriesWithCodes[] = array('name'=>'Peru', 'code'=>'PE', 'calling_code'=>51);
        $countriesWithCodes[] = array('name'=>'Philippines', 'code'=>'PH', 'calling_code'=>63);
        $countriesWithCodes[] = array('name'=>'Pitcairn Islands', 'code'=>'PN', 'calling_code'=>64);
        $countriesWithCodes[] = array('name'=>'Poland', 'code'=>'PL', 'calling_code'=>48);
        $countriesWithCodes[] = array('name'=>'Portugal', 'code'=>'PT', 'calling_code'=>351);
        $countriesWithCodes[] = array('name'=>'Puerto Rico', 'code'=>'PR', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Qatar', 'code'=>'QA', 'calling_code'=>974);
        $countriesWithCodes[] = array('name'=>'Romania', 'code'=>'RO', 'calling_code'=>40);
        $countriesWithCodes[] = array('name'=>'Russia', 'code'=>'RU', 'calling_code'=>7);
        $countriesWithCodes[] = array('name'=>'Rwanda', 'code'=>'RW', 'calling_code'=>250);
        $countriesWithCodes[] = array('name'=>'RAunion', 'code'=>'RE', 'calling_code'=>262);
        $countriesWithCodes[] = array('name'=>'Saint BarthAlemy', 'code'=>'BL', 'calling_code'=>590);
        $countriesWithCodes[] = array('name'=>'Saint Helena', 'code'=>'SH', 'calling_code'=>290);
        $countriesWithCodes[] = array('name'=>'Saint Kitts and Nevis', 'code'=>'KN', 'calling_code'=>1869);
        $countriesWithCodes[] = array('name'=>'Saint Lucia', 'code'=>'LC', 'calling_code'=>1758);
        $countriesWithCodes[] = array('name'=>'Saint Martin', 'code'=>'MF', 'calling_code'=>599);
        $countriesWithCodes[] = array('name'=>'Saint Pierre and Miquelon', 'code'=>'PM', 'calling_code'=>508);
        $countriesWithCodes[] = array('name'=>'Saint Vincent and the Grenadines', 'code'=>'VC', 'calling_code'=>1784);
        $countriesWithCodes[] = array('name'=>'Samoa', 'code'=>'WS', 'calling_code'=>685);
        $countriesWithCodes[] = array('name'=>'San Marino', 'code'=>'SM', 'calling_code'=>378);
        $countriesWithCodes[] = array('name'=>'Saudi Arabia', 'code'=>'SA', 'calling_code'=>966);
        $countriesWithCodes[] = array('name'=>'Senegal', 'code'=>'SN', 'calling_code'=>221);
        $countriesWithCodes[] = array('name'=>'Serbia', 'code'=>'RS', 'calling_code'=>381);
        $countriesWithCodes[] = array('name'=>'Serbia and Montenegro', 'code'=>'CS', 'calling_code'=>381);
        $countriesWithCodes[] = array('name'=>'Seychelles', 'code'=>'SC', 'calling_code'=>248);
        $countriesWithCodes[] = array('name'=>'Sierra Leone', 'code'=>'SL', 'calling_code'=>232);
        $countriesWithCodes[] = array('name'=>'Singapore', 'code'=>'SG', 'calling_code'=>65);
        $countriesWithCodes[] = array('name'=>'Slovakia', 'code'=>'SK', 'calling_code'=>421);
        $countriesWithCodes[] = array('name'=>'Slovenia', 'code'=>'SI', 'calling_code'=>386);
        $countriesWithCodes[] = array('name'=>'Solomon Islands', 'code'=>'SB', 'calling_code'=>677);
        $countriesWithCodes[] = array('name'=>'Somalia', 'code'=>'SO', 'calling_code'=>252);
        $countriesWithCodes[] = array('name'=>'South Africa', 'code'=>'ZA', 'calling_code'=>27);
        $countriesWithCodes[] = array('name'=>'South Georgia and the South Sandwich Islands', 'code'=>'GS', 'calling_code'=>500);
        $countriesWithCodes[] = array('name'=>'South Korea', 'code'=>'KR', 'calling_code'=>82);
        $countriesWithCodes[] = array('name'=>'Spain', 'code'=>'ES', 'calling_code'=>34);
        $countriesWithCodes[] = array('name'=>'Sri Lanka', 'code'=>'LK', 'calling_code'=>94);
        $countriesWithCodes[] = array('name'=>'Sudan', 'code'=>'SD', 'calling_code'=>249);
        $countriesWithCodes[] = array('name'=>'Suriname', 'code'=>'SR', 'calling_code'=>597);
        $countriesWithCodes[] = array('name'=>'Svalbard and Jan Mayen', 'code'=>'SJ', 'calling_code'=>47);
        $countriesWithCodes[] = array('name'=>'Swaziland', 'code'=>'SZ', 'calling_code'=>268);
        $countriesWithCodes[] = array('name'=>'Sweden', 'code'=>'SE', 'calling_code'=>46);
        $countriesWithCodes[] = array('name'=>'Switzerland', 'code'=>'CH', 'calling_code'=>41);
        $countriesWithCodes[] = array('name'=>'Syria', 'code'=>'SY', 'calling_code'=>693);
        $countriesWithCodes[] = array('name'=>'SA Toma and Prancipe', 'code'=>'ST', 'calling_code'=>239);
        $countriesWithCodes[] = array('name'=>'Taiwan', 'code'=>'TW', 'calling_code'=>886);
        $countriesWithCodes[] = array('name'=>'Tajikistan', 'code'=>'TJ', 'calling_code'=>992);
        $countriesWithCodes[] = array('name'=>'Tanzania', 'code'=>'TZ', 'calling_code'=>255);
        $countriesWithCodes[] = array('name'=>'Thailand', 'code'=>'TH', 'calling_code'=>66);
        $countriesWithCodes[] = array('name'=>'Timor-Leste', 'code'=>'TL', 'calling_code'=>670);
        $countriesWithCodes[] = array('name'=>'Togo', 'code'=>'TG', 'calling_code'=>228);
        $countriesWithCodes[] = array('name'=>'Tokelau', 'code'=>'TK', 'calling_code'=>690);
        $countriesWithCodes[] = array('name'=>'Tonga', 'code'=>'TO', 'calling_code'=>676);
        $countriesWithCodes[] = array('name'=>'Trinidad and Tobago', 'code'=>'TT', 'calling_code'=>868);
        $countriesWithCodes[] = array('name'=>'Tunisia', 'code'=>'TN', 'calling_code'=>216);
        $countriesWithCodes[] = array('name'=>'Turkey', 'code'=>'TR', 'calling_code'=>90);
        $countriesWithCodes[] = array('name'=>'Turkmenistan', 'code'=>'TM', 'calling_code'=>993);
        $countriesWithCodes[] = array('name'=>'Turks and Caicos Islands', 'code'=>'TC', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Tuvalu', 'code'=>'TV', 'calling_code'=>688);
        $countriesWithCodes[] = array('name'=>'U.S. Minor Outlying Islands', 'code'=>'UM', 'calling_code'=>246);
        $countriesWithCodes[] = array('name'=>'U.S. Miscellaneous Pacific Islands', 'code'=>'PU', 'calling_code'=>849);
        $countriesWithCodes[] = array('name'=>'U.S. Virgin Islands', 'code'=>'VI', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Uganda', 'code'=>'UG', 'calling_code'=>256);
        $countriesWithCodes[] = array('name'=>'Ukraine', 'code'=>'UA', 'calling_code'=>380);
        $countriesWithCodes[] = array('name'=>'Union of Soviet Socialist Republics', 'code'=>'SU', 'calling_code'=>7);
        $countriesWithCodes[] = array('name'=>'United Arab Emirates', 'code'=>'AE', 'calling_code'=>971);
        $countriesWithCodes[] = array('name'=>'United Kingdom', 'code'=>'GB', 'calling_code'=>44);
        $countriesWithCodes[] = array('name'=>'United States', 'code'=>'US', 'calling_code'=>1);
        $countriesWithCodes[] = array('name'=>'Uruguay', 'code'=>'UY', 'calling_code'=>598);
        $countriesWithCodes[] = array('name'=>'Uzbekistan', 'code'=>'UZ', 'calling_code'=>998);
        $countriesWithCodes[] = array('name'=>'Vanuatu', 'code'=>'VU', 'calling_code'=>678);
        $countriesWithCodes[] = array('name'=>'Vatican City', 'code'=>'VA', 'calling_code'=>379);
        $countriesWithCodes[] = array('name'=>'Venezuela', 'code'=>'VE', 'calling_code'=>58);
        $countriesWithCodes[] = array('name'=>'Vietnam', 'code'=>'VN', 'calling_code'=>84);
        $countriesWithCodes[] = array('name'=>'Wake Island', 'code'=>'WK', 'calling_code'=>1808);
        $countriesWithCodes[] = array('name'=>'Wallis and Futuna', 'code'=>'WF', 'calling_code'=>681);
        $countriesWithCodes[] = array('name'=>'Western Sahara', 'code'=>'EH', 'calling_code'=>212);
        $countriesWithCodes[] = array('name'=>'Yemen', 'code'=>'YE', 'calling_code'=>967);
        $countriesWithCodes[] = array('name'=>'Zambia', 'code'=>'ZM', 'calling_code'=>260);
        $countriesWithCodes[] = array('name'=>'Zimbabwe', 'code'=>'ZW', 'calling_code'=>263);
        $countriesWithCodes[] = array('name'=>'Aland Islands', 'code'=>'AX', 'calling_code'=>358);

        return $countriesWithCodes;
    }
}