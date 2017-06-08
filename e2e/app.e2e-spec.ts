import { UxPage } from './app.po';

describe('ux App', () => {
  let page: UxPage;

  beforeEach(() => {
    page = new UxPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
