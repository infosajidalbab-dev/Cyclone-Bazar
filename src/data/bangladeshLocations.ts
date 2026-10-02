export interface Upazila {
  id: string;
  name: string;
}

export interface District {
  id: string;
  name: string;
  defaultZoneCode: 'DHAKA_INSIDE' | 'DHAKA_SUBURB' | 'OUTSIDE_DHAKA';
  upazilas: string[];
}

export interface Division {
  id: string;
  name: string;
  districts: District[];
}

export const BANGLADESH_DIVISIONS: Division[] = [
  {
    id: 'dhaka',
    name: 'Dhaka',
    districts: [
      {
        id: 'dhaka_city',
        name: 'Dhaka (Metro)',
        defaultZoneCode: 'DHAKA_INSIDE',
        upazilas: ['Mirpur', 'Dhanmondi', 'Gulshan', 'Uttara', 'Mohammadpur', 'Badda', 'Motijheel', 'Old Dhaka', 'Khilgaon', 'Tejgaon']
      },
      {
        id: 'gazipur',
        name: 'Gazipur',
        defaultZoneCode: 'DHAKA_SUBURB',
        upazilas: ['Gazipur Sadar', 'Tongi', 'Kaliakair', 'Sreepur', 'Kapasia']
      },
      {
        id: 'narayanganj',
        name: 'Narayanganj',
        defaultZoneCode: 'DHAKA_SUBURB',
        upazilas: ['Narayanganj Sadar', 'Bandar', 'Araihazar', 'Sonargaon', 'Rupganj']
      },
      {
        id: 'savar',
        name: 'Dhaka (Rural/Savar)',
        defaultZoneCode: 'DHAKA_SUBURB',
        upazilas: ['Savar', 'Dhamrai', 'Keraniganj', 'Nawabganj', 'Dohar']
      },
      {
        id: 'tangail',
        name: 'Tangail',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Tangail Sadar', 'Mirzapur', 'Madhupur', 'Ghatail', 'Kalihati']
      }
    ]
  },
  {
    id: 'chattogram',
    name: 'Chattogram',
    districts: [
      {
        id: 'chattogram_city',
        name: 'Chattogram',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Kotwali', 'Panchlaish', 'Agrabad', 'Halishahar', 'Sitakunda', 'Hathazari', 'Patiya']
      },
      {
        id: 'coxs_bazar',
        name: 'Cox\'s Bazar',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Cox\'s Bazar Sadar', 'Ramu', 'Teknaf', 'Ukhia', 'Chakaria']
      },
      {
        id: 'cumilla',
        name: 'Cumilla',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Cumilla Adarsha Sadar', 'Laksam', 'Daudkandi', 'Chandina', 'Burichang']
      }
    ]
  },
  {
    id: 'rajshahi',
    name: 'Rajshahi',
    districts: [
      {
        id: 'rajshahi_dist',
        name: 'Rajshahi',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Boalia', 'Motihar', 'Rajpara', 'Godagari', 'Paba', 'Bagmara']
      },
      {
        id: 'bogura',
        name: 'Bogura',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Bogura Sadar', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Gabtali']
      },
      {
        id: 'pabna',
        name: 'Pabna',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Pabna Sadar', 'Ishwardi', 'Santhia', 'Chatmohar']
      }
    ]
  },
  {
    id: 'khulna',
    name: 'Khulna',
    districts: [
      {
        id: 'khulna_dist',
        name: 'Khulna',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Khulna Sadar', 'Sonadanga', 'Daulatpur', 'Dumuria', 'Rupsha']
      },
      {
        id: 'jashore',
        name: 'Jashore',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Jashore Sadar', 'Jhikargachha', 'Sharsha', 'Manirampur']
      }
    ]
  },
  {
    id: 'sylhet',
    name: 'Sylhet',
    districts: [
      {
        id: 'sylhet_dist',
        name: 'Sylhet',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Sylhet Sadar', 'Beanibazar', 'Golapganj', 'Biswanath', 'Zakiganj']
      }
    ]
  },
  {
    id: 'barishal',
    name: 'Barishal',
    districts: [
      {
        id: 'barishal_dist',
        name: 'Barishal',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Barishal Sadar', 'Bakerganj', 'Babuganj', 'Wazirpur']
      }
    ]
  },
  {
    id: 'rangpur',
    name: 'Rangpur',
    districts: [
      {
        id: 'rangpur_dist',
        name: 'Rangpur',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Rangpur Sadar', 'Pirganj', 'Badarganj', 'Mithapukur']
      }
    ]
  },
  {
    id: 'mymensingh',
    name: 'Mymensingh',
    districts: [
      {
        id: 'mymensingh_dist',
        name: 'Mymensingh',
        defaultZoneCode: 'OUTSIDE_DHAKA',
        upazilas: ['Mymensingh Sadar', 'Muktagachha', 'Trishal', 'Fulbaria']
      }
    ]
  }
];

export const DELIVERY_ZONES = [
  {
    id: 1,
    name: 'Inside Dhaka',
    code: 'DHAKA_INSIDE',
    baseCharge: 60,
    codAvailable: true,
    estimatedDaysMin: 1,
    estimatedDaysMax: 2,
    description: 'Same day / Next day doorstep delivery in Dhaka metropolitan area.'
  },
  {
    id: 2,
    name: 'Dhaka Suburbs',
    code: 'DHAKA_SUBURB',
    baseCharge: 100,
    codAvailable: true,
    estimatedDaysMin: 2,
    estimatedDaysMax: 3,
    description: 'Gazipur, Narayanganj, Savar, Keraniganj express delivery.'
  },
  {
    id: 3,
    name: 'Outside Dhaka',
    code: 'OUTSIDE_DHAKA',
    baseCharge: 130,
    codAvailable: true,
    estimatedDaysMin: 2,
    estimatedDaysMax: 4,
    description: 'Nationwide coverage via Steadfast Courier & Pathao logistics.'
  }
];
