import type { NextPage } from 'next';
import type { ReactElement, ReactNode } from 'react';

export interface QueryOptions {
  page?: number;
  limit?: number;
  language?: string;
}

export interface GetParams {
  slug: string;
  language?: string;
}

export interface SearchParamOptions {
  rating: string;
  question: string;

  [key: string]: unknown;
}

export interface ProductQueryOptions extends QueryOptions {
  shop_id: string;
  sortedBy: string;
  orderBy: string;
  name: string;
  price: string | string[];
  categories: string | string[];
  tags: string | string[];
  language?: string;
}

export interface PopularProductsQueryOptions {
  limit: number;
  shop_id: string;
  type_slug: string;
  range: number;
}

export interface FollowShopPopularProductsQueryOption {
  limit: number;
}

export interface TopShopQueryOptions {
  limit: number;
  name: string;
  range: number;
}

export interface CategoryQueryOptions extends QueryOptions {}

export interface TypeQueryOptions extends QueryOptions {}

export interface WishlistQueryOptions extends QueryOptions {}

export interface MyReportsQueryOptions extends QueryOptions {}

export interface MyQuestionQueryOptions extends QueryOptions {}

export interface ShopQueryOptions extends QueryOptions {
  is_active?: number;
}

export interface FollowedShopsQueryOptions extends QueryOptions {}

export interface OrderQueryOptions extends QueryOptions {
  orderBy: string;
  sortedBy: string;
}

export interface WishlistQueryOption extends QueryOptions {}

export interface ReviewQueryOptions extends QueryOptions {
  product_id: string;
  rating?: string;
}

export interface QuestionQueryOptions extends QueryOptions {
  product_id: string;
  question?: string;
}

export type NextPageWithLayout<P = {}> = NextPage<P> & {
  authorization?: boolean;
  getLayout?: (page: ReactElement) => ReactNode;
};

interface PaginatorInfo<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: any[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export interface SEO {
  metaTitle: string;
  metaDescription: string;
  ogTitle: string;
  ogDescription: string;
  ogImage: Attachment;
  twitterHandle: string;
  twitterCardType: string;
  metaTags: string;
  canonicalUrl: string;
}

export interface Settings {
  id: string;
  options: {
    siteTitle: string;
    copyrightText: string;
    siteLink: string;
    externalText: string;
    externalLink: string;
    siteSubtitle: string;
    currency: string;
    logo: Attachment;
    seo: SEO;
    contactDetails: ContactDetails;
    useOtp: Boolean;
    [key: string]: string | any;
  };
}

export interface ContactDetails {
  socials: [ShopSocials];
  contact: string;
  location: Location;
  website: string;
}

export interface ShopSocials {
  icon: string;
  url: string;
}

export interface Location {
  lat: number;
  lng: number;
  city: string;
  state: string;
  country: string;
  zip: string;
  formattedAddress: string;
}

export interface Attachment {
  id: string;
  original: string;
  thumbnail: string;
  __typename?: string;
}

export interface Shop {
  id: string;
  name: string;
  slug: string;
  description: string;
  orders_count: number;
  products_count: number;
  logo: Attachment;
  cover_image: Attachment;
  settings: {
    contact: string;
    website: string;
    socials: {
      icon: string;
      url: string;
    }[];
  };
  address: {
    street_address: string;
    city: string;
    state: string;
    zip: string;
    country: string;
  };
  owner: {
    id: string;
    name: string;
    email: string;
  };
}

export interface User {
  id: string;
  name: string;
  profile: {
    id: string;
    bio: string;
    contact: string;
    avatar: Attachment;
  };
  permissions?: {
    name: string;
  }[];
  wallet?: {
    available_points: number;
    created_at: string;
    customer_id: number;
    id: number;
    points_used: number;
    total_points: number;
    available_points_to_currency: number;
    updated_at: string;
  };
  role: string;
  created_at: string;
  updated_at: string;
  last_order: Order;
}

export interface UpdateProfileInput {
  id: string;
  name: string;
  profile: {
    id?: string;
    bio?: string;
    contact?: string;
    avatar?: Attachment | null;
  };
}

export interface ChangePasswordInput {
  oldPassword: string;
  newPassword: string;
}

export interface ContactInput {
  id: string;
  name: string;
  email: string;
  subject: string;
  message: string;
}

export interface LoginUserInput {
  email: string;
  password: string;
}

export interface RegisterUserInput {
  name: string;
  email: string;
  password: string;
}

export interface ForgetPasswordInput {
  email: string;
}

export interface ResetPasswordInput {
  token: string;
  email: string;
  password: string;
}

export interface VerifyForgetPasswordTokenInput {
  token: string;
  email: string;
}

export interface PasswordChangeResponse {
  success: boolean;
  message: string;
}

export interface AuthResponse {
  token: string;
  permissions: string[];
}

export interface CreateContactUsInput {
  name: string;
  email: string;
  subject: string;
  description: string;
}

export interface CreateAbuseReportInput {
  model_id: string;
  model_type: string;
  message: string;
}

export interface CreateFeedbackInput {
  model_id: string;
  model_type: string;
  positive?: boolean;
  negative?: boolean;
}

export interface CreateQuestionInput {
  question: string;
  product_id: string;
  shop_id: string;
}

export interface CreateReviewInput {
  product_id: string;
  shop_id: string;
  order_id: string;
  comment?: string;
  rating: number;
  photos?: Attachment[];
}

export interface UpdateReviewInput extends CreateReviewInput {
  id: string;
}

export interface ReviewResponse {
  product_id: string;
}

interface ConnectProductOrderPivot {
  product_id: string | number;
  order_quantity: number;
  unit_price: number;
  subtotal: number;
}

export interface CreateOrderInput {
  amount: number;
  total: number;
  paid_total: number;
  customer_contact: string | null;
  products: ConnectProductOrderPivot[];
  sales_tax: number;
  payment_gateway: PaymentGateway;
  use_wallet_points: boolean;
  payment_id?: string;
  shop_id?: string;
  customer_id?: string;
  isFullWalletPayment: boolean;
}

export enum PaymentGateway {
  FULL_WALLET_PAYMENT = 'FULL_WALLET_PAYMENT',
  STRIPE = 'STRIPE',
  PAYPAL = 'PAYPAL',
  RAZORPAY = 'RAZORPAY',
  MOLLIE = 'MOLLIE',
  PAYSTACK = 'PAYSTACK',
  BITPAY = 'BITPAY',
  COINBASE = 'COINBASE',
  ALIPAY = 'ALIPAY',
}

export enum OrderStatus {
  PENDING = 'order-pending',
  // PROCESSING = 'order-processing',
  COMPLETED = 'order-completed',
  CANCELLED = 'order-cancelled',
  REFUNDED = 'order-refunded',
  FAILED = 'order-failed',
  // AT_LOCAL_FACILITY = 'order-at-local-facility',
  // OUT_FOR_DELIVERY = 'order-out-for-delivery',
}

export enum PaymentStatus {
  PENDING = 'payment-pending',
  PROCESSING = 'payment-processing',
  SUCCESS = 'payment-success',
  FAILED = 'payment-failed',
  REVERSAL = 'payment-reversal',
  WALLET = 'payment-wallet',
}

export interface PaymentIntent {
  id: number | string;
  order_id: number | string;
  payment_gateway: PaymentGateway;
  tracking_number: string;
  payment_intent_info: PaymentIntentInfo;
}

export interface IOrderPaymentSummery {
  used_wallet_amount: number;
  is_payment_gateway_use: boolean;
  gateway_payment: number;
  amount_due: number;
  is_full_paid: boolean;
}

export interface PaymentIntentInfo {
  client_secret?: string;
  payment_id?: string;
  is_redirect?: boolean;
  redirect_url?: string;
  amount?: string;
  currency: string;
}

export interface CheckoutVerificationInput {
  amount: number;
  products: ConnectProductOrderPivot[];
}

export interface VerifiedCheckoutResponse {
  total_tax: number;
  shipping_charge: number;
  unavailable_products: string[];
  wallet_currency: number;
  wallet_amount: number;
}

export interface RatingCount {
  rating: number;
  total: number;
}

export interface Product {
  id: string;
  name: string;
  slug: string;
  description: string;
  price: number;
  sale_price: number;
  orders_count: number;
  total_downloads: number;
  image: Attachment;
  gallery: Attachment[];
  shop: Shop;
  created_at: string;
  updated_at: string;
  preview_url: string;
  my_review: Review[];
  shop_id: number;
  rating_count: RatingCount[];
  total_reviews: number;
  ratings: number;
  tags: Tag[];
  type: {
    id: string;
    name: string;
  };
  language: string;
  in_stock: number;
  is_external: boolean;
  external_product_url: string;
  external_product_button_text: string;
  video?: string[];
}

export interface ProductPaginator extends PaginatorInfo<Product> {}

export interface ReportsPaginator extends PaginatorInfo<Question> {}

export interface Category {
  id: string;
  name: string;
  slug: string;
}

export interface Type {
  id: string;
  name: string;
  icon: string;
  slug: string;
  promotional_sliders?: Attachment;
  created_at: string;
  updated_at: string;
  translated_languages: string[];
}

export interface CategoryPaginator extends PaginatorInfo<Category> {}

export interface TypePaginator extends PaginatorInfo<Type> {}

export interface ShopPaginator extends PaginatorInfo<Shop> {}

export interface Order {
  id: number | string;
  tracking_number: string;
  customer_id: number | string;
  amount: number;
  children: Order[] | undefined;
  total: number;
  paid_total: number;
  payment_gateway: string;
  products: Product[];
  created_at: Date;
  updated_at: Date;
  payment_intent?: PaymentIntent;
  order_status: string;
  payment_status: string;
  wallet_point: {
    amount?: number;
  };
  sales_tax: number;
  reviews?: Review[];
}

export interface DigitalFile {
  id: string;
  fileable: Product;
}

export interface OrderedFile {
  id: string;
  purchase_key: string;
  digital_file_id: string;
  customer_id: string;
  order_id: string;
  file: DigitalFile;
  created_at: string;
  updated_at: string;
  tracking_number: string;
  order: {
    payment_status: string;
    tracking_number: string;
    id: string;
  };
}

export interface Feedback {
  id: string;
  user_id: string;
  model_type: string;
  model_id: string;
  positive: boolean;
  negative: boolean;
  created_at: string;
  updated_at: string;
}

export interface Question {
  id: string;
  answer: string;
  my_feedback: Feedback;
  negative_feedbacks_count: number;
  positive_feedbacks_count: number;
  product: Product;
  question: string;
  created_at: string;
  updated_at: string;
}

export interface Review {
  id: string;
  name: string;
  rating: number;
  comment: string;
  photos: Attachment[];
  user: User;
  product: Product;
  shop: Shop;
  feedbacks: Feedback[];
  positive_feedbacks_count: number;
  negative_feedbacks_count: number;
  my_feedback: Feedback;
  order_id?: string;
  created_at: string;
  updated_at: string;
}

export interface Wishlist {
  id: string;
  product: Product;
  product_id: string;
  user: User[];
  user_id: string;
}
export interface TermsAndConditionsQueryOptions extends QueryOptions {
  title: string;
  issued_by: string;
  type: string;
  orderBy: string;
  sortedBy: string;
  shop_id: string;
  is_approved: boolean;
}
export interface TermsAndConditions {
  id: string;
  translated_languages: string[];
  title: string;
  description: string;
  shop_id?: string;
  user_id: string;
  created_at: string;
  updated_at: string;
  deleted_at?: string;
  is_approved?: boolean;
  issued_by?: string;
  type?: string;
  shop?: Shop;
}

export interface FAQS {
  id: string;
  faq_title: string;
  faq_description: string;
  slug: string;
  faq_type: string;
  issued_by: string;
  language: string;
  shop_id?: Shop;
  user_id: User;
  translated_languages: string[];
  created_at: string;
  updated_at: string;
}

export interface TagPaginator extends PaginatorInfo<Tag> {}

export interface OrderPaginator extends PaginatorInfo<Order> {}

export interface OrderedFilePaginator extends PaginatorInfo<OrderedFile> {}

export interface ReviewPaginator extends PaginatorInfo<Review> {}

export interface WishlistPaginator extends PaginatorInfo<Wishlist> {}

export interface QuestionPaginator extends PaginatorInfo<Question> {}

export interface TermsAndConditionsPaginator
  extends PaginatorInfo<TermsAndConditions> {}

export interface SettingsQueryOptions extends QueryOptions {
  language?: string;
}

export interface Tag {
  id: string;
  name: string;
  slug: string;
}

export interface Address {
  city: string;
  country: string;
  state: string;
  street_address: string;
  zip: string;
}

export interface Feedback {
  id: string;
  user_id: string;
  model_type: string;
  model_id: string;
  positive: boolean;
  negative: boolean;
  created_at: string;
  updated_at: string;
}

export interface Review {
  id: string;
  name: string;
  rating: number;
  comment: string;
  photos: Attachment[];
  user: User;
  product: Product;
  shop: Shop;
  feedbacks: Feedback[];
  positive_feedbacks_count: number;
  negative_feedbacks_count: number;
  my_feedback: Feedback;
  created_at: string;
  updated_at: string;
}

export interface CreateReviewInput {
  product_id: string;
  shop_id: string;
  comment?: string;
  rating: number;
  photos?: Attachment[];
}

export interface CreateAbuseReportInput {
  model_id: string;
  message: string;
}

export interface Question {
  id: string;
  answer: string;
  my_feedback: Feedback;
  negative_feedbacks_count: number;
  positive_feedbacks_count: number;
  question: string;
  created_at: string;
  updated_at: string;
}

export interface CreateQuestionInput {
  question: string;
  product_id: string;
  shop_id: string;
}

export interface PaymentIntentCollection {
  tracking_number?: string;
  payment_intent_info?: PaymentIntentInfo;
  payment_gateway?: string;
}

export interface CreateOrderPaymentInput {
  tracking_number: string;
  payment_gateway: string;
}

export interface Card {
  expires: string;
  network: string;
  origin: string;
  owner_name: string;
  payment_gateway_id: number | string;
  default_card: number;
}

export interface DownloadableFile {
  id: string;
  purchase_key: string;
  digital_file_id: string;
  customer_id: string;
  file: DigitalFile;
  created_at: string;
  updated_at: string;
}

export interface DownloadableFilePaginator
  extends PaginatorInfo<DownloadableFile> {}

export interface FaqsQueryOptions extends QueryOptions {
  faq_title?: string;
  issued_by?: string;
  faq_type?: string;
  orderBy?: string;
  sortedBy?: string;
  shop_id?: string;
}

export interface FAQS {
  id: string;
  faq_title: string;
  faq_description: string;
  slug: string;
  faq_type: string;
  issued_by: string;
  language: string;
  shop_id?: Shop;
  user_id: User;
  translated_languages: string[];
  created_at: string;
  updated_at: string;
}

export interface FaqsPaginator extends PaginatorInfo<FAQS> {}

export interface GoogleMapLocation {
  lat?: number | string;
  lng?: number | string;
  street_number?: string;
  route?: string;
  street_address?: string;
  city?: string;
  state?: string;
  country?: string;
  zip?: string;
  formattedAddress?: string;
  formatted_address?: string;
}

export interface BecomeSeller {
  commission: BecomeSellerCommission[];
  page_options: {
    page_options: BecomeSellerPageOptions;
  };
}

export interface BecomeSellerCommission {
  commission: number;
  description: string;
  image: Attachment;
  level: string;
  max_balance: string;
  min_balance: number;
  sub_level: string;
}

export interface BecomeSellerPageOptions {
  banner: BecomeSellerBanner;
  commissionDescription: string;
  commissionTitle: string;
  defaultCommissionDetails: string;
  defaultCommissionRate: number;
  faqDescription: string;
  faqTitle: string;
  isMultiCommissionRate: boolean;
  faqItems: {
    description: string;
    title: string;
  }[];
  purposeDescription: string;
  purposeItems: BusinessPurpose[];
  purposeTitle: string;
  sellingStepsDescription: string;
  sellingStepsItem: SellingStep[];
  sellingStepsTitle: string;
  dashboard: BecomeSellerDashboardShowcase;
  guidelineTitle: string;
  guidelineDescription: string;
  guidelineItems: {
    title: string;
    link?: string;
  }[];
  sellerOpportunity: BecomeSellerDashboardShowcase;
  userStoryTitle: string;
  userStoryDescription?: string;
  userStories: {
    title: string;
    description: string;
    link: string;
    thumbnail?: Attachment;
  }[];
  contact: {
    title: string;
    description: string;
  };
}
export interface BusinessPurpose {
  description: string;
  title: string;
  icon: {
    value: string;
  };
}
export interface BecomeSellerBanner {
  description: string;
  image: Attachment;
  newsTickerTitle: string;
  newsTickerURL: string;
  title: string;
  button1Name?: string;
  button1Link?: string;
  button2Name?: string;
  button2Link?: string;
}
export interface SellingStep {
  title: string;
  description: string;
  image: Attachment;
}
export interface BecomeSellerDashboardShowcase {
  title: string;
  description: string;
  image: Attachment;
  buttonName?: string;
  buttonLink?: string;
  button2Name?: string;
  button2Link?: string;
}

export interface Commission {
  commission: number;
  created_at?: Date;
  description: string;
  id?: number;
  image?: Attachment;
  language?: string;
  level?: string;
  max_balance?: string | number;
  min_balance?: string | number;
  sub_level?: string;
  updated_at?: Date;
}
export type AlertProps = {
  message: string | null;
  variant?:
    | 'info'
    | 'warning'
    | 'error'
    | 'success'
    | 'infoOutline'
    | 'warningOutline'
    | 'errorOutline'
    | 'successOutline';
  closeable?: boolean;
  onClose?: React.Dispatch<React.SetStateAction<any>>;
  className?: string;
  children?: React.ReactNode;
  childClassName?: string;
};
